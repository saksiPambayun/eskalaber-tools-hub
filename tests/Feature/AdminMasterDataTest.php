<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Fine;
use App\Models\Loan;
use App\Models\Place;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminMasterDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_open_all_previously_missing_pages(): void
    {
        $admin = User::factory()->create(['role' => 'SUPERADMIN']);
        $category = Category::create(['name' => 'Kategori Uji']);
        $fine = $this->makeFine();

        $this->actingAs($admin)
            ->get('/admin/categories/add')->assertOk()->assertSee('Form Tambah Kategori');

        $this->actingAs($admin)
            ->get('/admin/categories/update/' . $category->id)
            ->assertOk()
            ->assertSee('Kategori Uji');

        $this->actingAs($admin)
            ->get('/admin/fines/detail/' . $fine->id)
            ->assertOk()
            ->assertSee('Detail Pembayaran #' . $fine->id);
    }

    public function test_admin_can_create_and_update_category(): void
    {
        $admin = User::factory()->create(['role' => 'SUPERADMIN']);

        $this->actingAs($admin)->post('/admin/categories/create', ['name' => 'Kategori Baru'])
            ->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('categories', ['name' => 'Kategori Baru']);

        $category = Category::where('name', 'Kategori Baru')->firstOrFail();
        $this->actingAs($admin)->post('/admin/categories/update/' . $category->id, ['name' => 'Kategori Diubah'])
            ->assertRedirect('/admin/categories');
        $this->assertDatabaseHas('categories', ['name' => 'Kategori Diubah']);
    }

    public function test_delete_master_data_used_by_tool_is_blocked(): void
    {
        $admin = User::factory()->create(['role' => 'SUPERADMIN']);
        $category = Category::create(['name' => 'Kategori Dipakai']);
        $place = Place::create(['name' => 'Place Dipakai']);

        Tool::create([
            'name' => 'Alat Uji',
            'code' => 'TEST-' . uniqid(),
            'category_id' => $category->id,
            'place_id' => $place->id,
            'stock' => 1,
            'status' => 'available',
        ]);

        $this->actingAs($admin)->delete('/admin/categories/delete/' . $category->id)
            ->assertRedirect('/admin/categories')->assertSessionHas('error');
        $this->actingAs($admin)->delete('/admin/places/delete/' . $place->id)
            ->assertRedirect('/admin/places')->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
        $this->assertDatabaseHas('places', ['id' => $place->id]);

        $unused = Category::create(['name' => 'Kategori Kosong']);
        $this->actingAs($admin)->delete('/admin/categories/delete/' . $unused->id)
            ->assertRedirect('/admin/categories')->assertSessionHas('success');
        $this->assertDatabaseMissing('categories', ['id' => $unused->id]);
    }

    public function test_profile_change_password_routes_exist(): void
    {
        $user = User::factory()->create(['role' => 'USER']);
        $this->actingAs($user)->get('/user/profile')->assertOk();
        $this->actingAs($user)->get('/user/profile/change-password')->assertOk();
        $this->actingAs($user)->post('/user/profile/change-password', [
            'current_password' => 'password',
            'new_password' => 'new-password-123',
            'new_password_confirmation' => 'new-password-123',
        ])->assertSessionHas('success');

        $toolsman = User::factory()->create(['role' => 'TOOLSMAN']);
        $this->actingAs($toolsman)->get('/toolsman/profile')->assertOk();
        $this->actingAs($toolsman)->get('/toolsman/profile/change-password')->assertOk();
        $this->actingAs($toolsman)->post('/toolsman/profile/change-password', [
            'current_password' => 'password',
            'new_password' => 'new-password-456',
            'new_password_confirmation' => 'new-password-456',
        ])->assertSessionHas('success');
    }

    public function test_fine_status_update_rejects_invalid_value(): void
    {
        $admin = User::factory()->create(['role' => 'SUPERADMIN']);
        $fine = $this->makeFine();

        $this->actingAs($admin)->post('/admin/fines/update-status/' . $fine->id, ['status' => 'invalid'])
            ->assertSessionHasErrors('status');

        $this->assertDatabaseHas('fines', ['id' => $fine->id, 'status' => 'unpaid']);
    }

    private function makeFine(): Fine
    {
        $borrower = User::factory()->create(['role' => 'USER']);
        $category = Category::create(['name' => 'Kategori Denda']);
        $place = Place::create(['name' => 'Place Denda']);

        $tool = Tool::create([
            'name' => 'Alat Denda',
            'code' => 'FINE-' . uniqid(),
            'category_id' => $category->id,
            'place_id' => $place->id,
            'stock' => 1,
            'status' => 'available',
        ]);

        $loan = Loan::create([
            'user_id' => $borrower->id,
            'tool_id' => $tool->id,
            'loan_date' => now()->subDays(3),
            'return_date' => now()->subDay(),
            'status' => 'late',
        ]);

        return Fine::create([
            'loan_id' => $loan->id,
            'user_id' => $borrower->id,
            'amount' => 25000,
            'description' => 'Denda keterlambatan',
            'late_days' => 1,
            'status' => 'unpaid',
        ]);
    }
}
