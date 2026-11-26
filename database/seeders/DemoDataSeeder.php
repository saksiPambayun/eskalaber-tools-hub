<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Department;
use App\Models\Fine;
use App\Models\FineSetting;
use App\Models\Loan;
use App\Models\PaymentMethod;
use App\Models\Place;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PaymentMethodSeeder::class);
        FineSetting::current();

        $dept = Department::firstOrCreate(['name' => 'Teknik'], ['name' => 'Teknik']);
        $cat = Category::firstOrCreate(['name' => 'Elektronik']);
        $place = Place::firstOrCreate(['name' => 'Lab Utama']);

        $admin = User::updateOrCreate(
            ['email' => 'admin@demo.test'],
            ['name' => 'Admin Demo', 'password' => Hash::make('password'), 'role' => 'SUPERADMIN', 'department_id' => $dept->id]
        );
        $toolsman = User::updateOrCreate(
            ['email' => 'toolsman@demo.test'],
            ['name' => 'Toolsman Demo', 'password' => Hash::make('password'), 'role' => 'TOOLSMAN', 'department_id' => $dept->id]
        );
        $user = User::updateOrCreate(
            ['email' => 'user@demo.test'],
            ['name' => 'User Demo', 'password' => Hash::make('password'), 'role' => 'USER', 'department_id' => $dept->id]
        );

        $tool1 = Tool::updateOrCreate(['code' => 'DEMO-001'], [
            'name' => 'Multimeter Digital', 'category_id' => $cat->id,
            'place_id' => $place->id, 'stock' => 5, 'status' => 'available', 'description' => 'Alat demo',
        ]);
        $tool2 = Tool::updateOrCreate(['code' => 'DEMO-002'], [
            'name' => 'Oscilloscope', 'category_id' => $cat->id,
            'place_id' => $place->id, 'stock' => 2, 'status' => 'available', 'description' => 'Alat demo',
        ]);

        // Pending loan
        Loan::updateOrCreate(
            ['user_id' => $user->id, 'tool_id' => $tool1->id, 'status' => 'pending', 'notes' => 'DEMO-PENDING'],
            ['loan_date' => now()->toDateString(), 'return_date' => now()->addDays(3)->toDateString()]
        );

        // Approved loan overdue (ready for simulate return + fine)
        Loan::updateOrCreate(
            ['user_id' => $user->id, 'tool_id' => $tool2->id, 'status' => 'approved', 'notes' => 'DEMO-OVERDUE'],
            ['loan_date' => now()->subDays(7)->toDateString(), 'return_date' => now()->subDays(2)->toDateString()]
        );

        // Returned loan with unpaid fine
        $returnedLoan = Loan::updateOrCreate(
            ['user_id' => $user->id, 'tool_id' => $tool1->id, 'status' => 'returned', 'notes' => 'DEMO-RETURNED-FINE'],
            ['loan_date' => now()->subDays(10)->toDateString(), 'return_date' => now()->subDays(5)->toDateString(), 'actual_return_date' => now()->subDays(2)->toDateString()]
        );
        Fine::updateOrCreate(
            ['loan_id' => $returnedLoan->id],
            ['user_id' => $user->id, 'amount' => 30000, 'description' => 'Denda keterlambatan 3 hari', 'late_days' => 3, 'status' => 'unpaid']
        );

        // Pending verification fine with dummy proof
        $returnedLoan2 = Loan::updateOrCreate(
            ['user_id' => $user->id, 'tool_id' => $tool2->id, 'status' => 'returned', 'notes' => 'DEMO-PENDING-VERIFY'],
            ['loan_date' => now()->subDays(12)->toDateString(), 'return_date' => now()->subDays(8)->toDateString(), 'actual_return_date' => now()->subDays(6)->toDateString()]
        );
        $proofPath = $this->makeDummyProof();
        $pm = PaymentMethod::where('is_active', true)->first();
        Fine::updateOrCreate(
            ['loan_id' => $returnedLoan2->id],
            [
                'user_id' => $user->id, 'amount' => 20000, 'description' => 'Denda keterlambatan 2 hari',
                'late_days' => 2, 'status' => 'pending_verification',
                'payment_method_id' => $pm?->id, 'payment_method' => $pm?->name,
                'proof_image' => $proofPath, 'submitted_at' => now()->subHour(),
            ]
        );

        // Rejected fine (user can resubmit)
        $returnedLoan3 = Loan::updateOrCreate(
            ['user_id' => $user->id, 'tool_id' => $tool1->id, 'status' => 'returned', 'notes' => 'DEMO-REJECTED-FINE'],
            ['loan_date' => now()->subDays(15)->toDateString(), 'return_date' => now()->subDays(10)->toDateString(), 'actual_return_date' => now()->subDays(8)->toDateString()]
        );
        Fine::updateOrCreate(
            ['loan_id' => $returnedLoan3->id],
            [
                'user_id' => $user->id, 'amount' => 20000, 'description' => 'Denda keterlambatan 2 hari',
                'late_days' => 2, 'status' => 'rejected',
                'payment_method_id' => $pm?->id, 'payment_method' => $pm?->name,
                'proof_image' => $proofPath, 'submitted_at' => now()->subDays(1),
                'reviewed_by' => $admin->id, 'reviewed_at' => now()->subHours(12), 'review_note' => 'Bukti tidak jelas, unggah ulang.',
            ]
        );

        // Paid fine
        $returnedLoan4 = Loan::updateOrCreate(
            ['user_id' => $user->id, 'tool_id' => $tool2->id, 'status' => 'returned', 'notes' => 'DEMO-PAID-FINE'],
            ['loan_date' => now()->subDays(20)->toDateString(), 'return_date' => now()->subDays(15)->toDateString(), 'actual_return_date' => now()->subDays(14)->toDateString()]
        );
        Fine::updateOrCreate(
            ['loan_id' => $returnedLoan4->id],
            [
                'user_id' => $user->id, 'amount' => 10000, 'description' => 'Denda keterlambatan 1 hari',
                'late_days' => 1, 'status' => 'paid',
                'payment_method_id' => $pm?->id, 'payment_method' => $pm?->name,
                'payment_date' => now()->subDays(13)->toDateString(),
                'reviewed_by' => $toolsman->id, 'reviewed_at' => now()->subDays(13),
            ]
        );

        $this->command?->info('Demo data siap.');
        $this->command?->info('Login: admin@demo.test / toolsman@demo.test / user@demo.test (password: password)');
    }

    private function makeDummyProof(): string
    {
        $dir = public_path('uploads/fine-proofs');
        File::ensureDirectoryExists($dir);
        $path = 'uploads/fine-proofs/demo-proof.png';
        $full = public_path($path);
        if (!file_exists($full)) {
            // tiny valid PNG
            $png = base64_decode('R0lGODlhAQABAPAAAP///wAAACH5BAAAAAAALAAAAAABAAEAAAICRAEAOw=='); // tiny gif also ok; but we said png/jpg/jpeg. Better make png
            // actually use a tiny PNG
            $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+ip1sAAAAASUVORK5CYII=');
            file_put_contents($full, $png);
        }
        return $path;
    }
}
