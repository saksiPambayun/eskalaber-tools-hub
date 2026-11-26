<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Fine;
use App\Models\FineSetting;
use App\Models\Loan;
use App\Models\PaymentMethod;
use App\Models\Place;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LoanFinePaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_borrow_again_after_approved_same_or_different_tool(): void
    {
        $user = User::factory()->create(['role' => 'USER']);
        $tool = $this->makeTool(['stock' => 5]);
        $tool2 = $this->makeTool(['stock' => 2]);

        $payload = [
            'tool_id' => $tool->id,
            'loan_date' => now()->toDateString(),
            'return_date' => now()->addDays(2)->toDateString(),
            'notes' => 'test',
        ];

        $this->actingAs($user)->post(route('user.loans.create'), $payload)
            ->assertRedirect(route('user.loans.index'));

        // Same tool again while previous is still pending/approved-eligible - allowed
        $this->actingAs($user)->post(route('user.loans.create'), $payload)
            ->assertRedirect(route('user.loans.index'));

        // Different tool also allowed
        $this->actingAs($user)->post(route('user.loans.create'), array_merge($payload, ['tool_id' => $tool2->id]))
            ->assertRedirect(route('user.loans.index'));

        $this->assertEquals(3, Loan::where('user_id', $user->id)->count());
    }

    public function test_approve_blocks_when_stock_insufficient_and_prevents_double_approve(): void
    {
        $admin = User::factory()->create(['role' => 'SUPERADMIN']);
        $tool = $this->makeTool(['stock' => 1]);
        $user1 = User::factory()->create(['role' => 'USER']);
        $user2 = User::factory()->create(['role' => 'USER']);

        $loan1 = Loan::create([
            'user_id' => $user1->id, 'tool_id' => $tool->id,
            'loan_date' => now()->toDateString(), 'return_date' => now()->addDays(2)->toDateString(), 'status' => 'pending',
        ]);
        $loan2 = Loan::create([
            'user_id' => $user2->id, 'tool_id' => $tool->id,
            'loan_date' => now()->toDateString(), 'return_date' => now()->addDays(2)->toDateString(), 'status' => 'pending',
        ]);

        $this->actingAs($admin)->post(route('admin.loans.update-status', $loan1->id), ['status' => 'approved'])
            ->assertRedirect(route('admin.loans.index'));

        $this->actingAs($admin)->post(route('admin.loans.update-status', $loan2->id), ['status' => 'approved'])
            ->assertSessionHas('error');

        $this->actingAs($admin)->post(route('admin.loans.update-status', $loan1->id), ['status' => 'approved'])
            ->assertSessionHas('error');

        $this->assertEquals(0, $tool->fresh()->stock);
        $this->assertEquals('approved', $loan1->fresh()->status);
        $this->assertEquals('pending', $loan2->fresh()->status);
    }

    public function test_return_creates_fine_when_late_and_fine_not_duplicated(): void
    {
        $toolsman = User::factory()->create(['role' => 'TOOLSMAN']);
        $tool = $this->makeTool(['stock' => 0, 'status' => 'borrowed']);
        $user = User::factory()->create(['role' => 'USER']);

        $loan = Loan::create([
            'user_id' => $user->id,
            'tool_id' => $tool->id,
            'loan_date' => now()->subDays(5)->toDateString(),
            'return_date' => now()->subDays(2)->toDateString(),
            'status' => 'approved',
        ]);

        $this->actingAs($toolsman)->post(route('toolsman.loans.returned', $loan->id))
            ->assertRedirect(route('toolsman.loans.index'));

        $loan->refresh();
        $this->assertEquals('returned', $loan->status);
        $this->assertNotNull($loan->actual_return_date);
        $this->assertEquals(1, $tool->fresh()->stock);

        $this->assertDatabaseHas('fines', [
            'loan_id' => $loan->id,
            'user_id' => $user->id,
            'status' => 'unpaid',
            'late_days' => 2,
            'amount' => 20000,
        ]);
        $this->assertEquals(1, Fine::where('loan_id', $loan->id)->count());
    }

    public function test_user_can_submit_payment_proof_and_admin_can_verify(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'SUPERADMIN']);
        $user = User::factory()->create(['role' => 'USER']);
        $pm = PaymentMethod::create(['name' => 'BCA', 'type' => 'bank', 'account_number' => '123', 'account_name' => 'Test', 'is_active' => true]);
        $fine = $this->makeFine($user, ['status' => 'unpaid']);

        $file = UploadedFile::fake()->image('bukti.jpg');

        $this->actingAs($user)->post(route('user.fines.pay', $fine->id), [
            'payment_method_id' => $pm->id,
            'payment_reference' => 'REF123',
            'proof_image' => $file,
        ])->assertRedirect(route('user.fines.detail', $fine->id));

        $fine->refresh();
        $this->assertEquals('pending_verification', $fine->status);
        $this->assertNotNull($fine->proof_image);
        $this->assertFileExists(public_path($fine->proof_image));

        $this->actingAs($admin)->post(route('admin.fines.verify', $fine->id), [
            'action' => 'approve',
            'review_note' => 'ok',
        ])->assertSessionHas('success');

        $this->assertEquals('paid', $fine->fresh()->status);
    }

    public function test_toolsman_can_reject_and_user_can_resubmit_proof(): void
    {
        $toolsman = User::factory()->create(['role' => 'TOOLSMAN']);
        $user = User::factory()->create(['role' => 'USER']);
        $pm = PaymentMethod::create(['name' => 'QRIS', 'type' => 'qris', 'is_active' => true]);
        $fine = $this->makeFine($user, ['status' => 'unpaid']);

        $this->actingAs($user)->post(route('user.fines.pay', $fine->id), [
            'payment_method_id' => $pm->id,
            'proof_image' => UploadedFile::fake()->image('bukti.png'),
        ])->assertRedirect();

        $this->actingAs($toolsman)->post(route('toolsman.fines.verify', $fine->id), [
            'action' => 'reject',
            'review_note' => 'tidak jelas',
        ])->assertSessionHas('success');

        $this->assertEquals('rejected', $fine->fresh()->status);

        $this->actingAs($user)->post(route('user.fines.pay', $fine->id), [
            'payment_method_id' => $pm->id,
            'proof_image' => UploadedFile::fake()->image('bukti2.jpg'),
        ])->assertRedirect();

        $this->assertEquals('pending_verification', $fine->fresh()->status);
    }

    public function test_payment_proof_must_be_png_jpg_jpeg(): void
    {
        $user = User::factory()->create(['role' => 'USER']);
        $pm = PaymentMethod::create(['name' => 'BRI', 'type' => 'bank', 'is_active' => true]);
        $fine = $this->makeFine($user, ['status' => 'unpaid']);

        $this->actingAs($user)->post(route('user.fines.pay', $fine->id), [
            'payment_method_id' => $pm->id,
            'proof_image' => UploadedFile::fake()->create('bukti.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors('proof_image');

        $this->assertEquals('unpaid', $fine->fresh()->status);
    }

    public function test_admin_and_toolsman_can_manage_payment_methods(): void
    {
        $admin = User::factory()->create(['role' => 'SUPERADMIN']);
        $toolsman = User::factory()->create(['role' => 'TOOLSMAN']);

        $this->actingAs($admin)->post(route('admin.payment-methods.create'), [
            'name' => 'Dana', 'type' => 'ewallet', 'account_number' => '08123', 'account_name' => 'A', 'is_active' => 1,
        ])->assertRedirect(route('admin.payment-methods.index'));
        $this->assertDatabaseHas('payment_methods', ['name' => 'Dana']);

        $this->actingAs($toolsman)->post(route('toolsman.payment-methods.create'), [
            'name' => 'Mandiri', 'type' => 'bank', 'account_number' => '999', 'account_name' => 'B', 'is_active' => 1,
        ])->assertRedirect(route('toolsman.payment-methods.index'));
        $this->assertDatabaseHas('payment_methods', ['name' => 'Mandiri']);
    }

    public function test_unpaid_or_rejected_fine_blocks_new_loan(): void
    {
        $user = User::factory()->create(['role' => 'USER']);
        $tool = $this->makeTool();
        $fine = $this->makeFine($user, ['status' => 'unpaid']);

        $payload = [
            'tool_id' => $tool->id,
            'loan_date' => now()->toDateString(),
            'return_date' => now()->addDays(2)->toDateString(),
        ];

        $this->actingAs($user)->post(route('user.loans.create'), $payload)
            ->assertSessionHas('error');
        $this->assertDatabaseMissing('loans', ['user_id' => $user->id, 'tool_id' => $tool->id, 'status' => 'pending']);

        $fine->update(['status' => 'rejected']);
        $this->actingAs($user)->post(route('user.loans.create'), $payload)
            ->assertSessionHas('error');

        $fine->update(['status' => 'pending_verification']);
        $this->actingAs($user)->post(route('user.loans.create'), $payload)
            ->assertRedirect(route('user.loans.index'));
    }

    public function test_admin_and_toolsman_can_update_global_fine_rate_with_audit(): void
    {
        $admin = User::factory()->create(['role' => 'SUPERADMIN']);
        $toolsman = User::factory()->create(['role' => 'TOOLSMAN']);

        $this->actingAs($admin)->post(route('admin.fine-setting.update'), ['rate_per_day' => 15000])
            ->assertSessionHas('success');
        $this->assertDatabaseHas('fine_settings', ['id' => 1, 'rate_per_day' => 15000]);
        $this->assertDatabaseHas('fine_setting_histories', ['old_rate' => 10000, 'new_rate' => 15000, 'changed_by' => $admin->id]);

        $this->actingAs($toolsman)->post(route('toolsman.fine-setting.update'), ['rate_per_day' => 20000])
            ->assertSessionHas('success');
        $this->assertDatabaseHas('fine_settings', ['id' => 1, 'rate_per_day' => 20000]);
        $this->assertDatabaseHas('fine_setting_histories', ['old_rate' => 15000, 'new_rate' => 20000, 'changed_by' => $toolsman->id]);

        $this->actingAs($admin)->post(route('admin.fine-setting.update'), ['rate_per_day' => -1])
            ->assertSessionHasErrors('rate_per_day');
        $this->actingAs($admin)->post(route('admin.fine-setting.update'), ['rate_per_day' => 1000001])
            ->assertSessionHasErrors('rate_per_day');
    }

    public function test_new_fine_rate_applies_only_to_new_fines(): void
    {
        $admin = User::factory()->create(['role' => 'SUPERADMIN']);
        $toolsman = User::factory()->create(['role' => 'TOOLSMAN']);
        $user = User::factory()->create(['role' => 'USER']);

        FineSetting::current()->update(['rate_per_day' => 10000]);
        $existingFine = $this->makeFine($user, ['status' => 'unpaid', 'amount' => 20000, 'late_days' => 2]);

        $this->actingAs($admin)->post(route('admin.fine-setting.update'), ['rate_per_day' => 25000]);

        // Existing fine snapshot unchanged
        $this->assertEquals(20000, $existingFine->fresh()->amount);

        // New late return uses new rate
        $tool = $this->makeTool(['stock' => 0, 'status' => 'borrowed']);
        $loan = Loan::create([
            'user_id' => $user->id,
            'tool_id' => $tool->id,
            'loan_date' => now()->subDays(5)->toDateString(),
            'return_date' => now()->subDays(2)->toDateString(),
            'status' => 'approved',
        ]);

        $this->actingAs($toolsman)->post(route('toolsman.loans.returned', $loan->id))
            ->assertRedirect(route('toolsman.loans.index'));

        $this->assertDatabaseHas('fines', [
            'loan_id' => $loan->id,
            'amount' => 50000, // 2 days * 25000
            'late_days' => 2,
        ]);
    }

    private function makeTool(array $attrs = []): Tool
    {
        $category = Category::create(['name' => 'Cat ' . uniqid()]);
        $place = Place::create(['name' => 'Place ' . uniqid()]);

        return Tool::create(array_merge([
            'name' => 'Tool ' . uniqid(),
            'code' => 'T-' . uniqid(),
            'category_id' => $category->id,
            'place_id' => $place->id,
            'stock' => 2,
            'status' => 'available',
        ], $attrs));
    }

    private function makeFine(User $user, array $attrs = []): Fine
    {
        $tool = $this->makeTool();
        $loan = Loan::create([
            'user_id' => $user->id,
            'tool_id' => $tool->id,
            'loan_date' => now()->subDays(5)->toDateString(),
            'return_date' => now()->subDays(2)->toDateString(),
            'actual_return_date' => now()->toDateString(),
            'status' => 'returned',
        ]);

        return Fine::create(array_merge([
            'loan_id' => $loan->id,
            'user_id' => $user->id,
            'amount' => 20000,
            'description' => 'Terlambat',
            'late_days' => 2,
            'status' => 'unpaid',
        ], $attrs));
    }
}
