<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fine;
use App\Models\Loan;
use Carbon\Carbon;

class FineSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil semua loans
        $loans = Loan::all();

        if ($loans->isEmpty()) {
            $this->command->warn('Tidak ada loans! Buat loans dulu dengan LoanSeeder');
            return;
        }

        // Buat fines untuk beberapa loans
        foreach ($loans as $index => $loan) {
            // Cek apakah loan sudah punya fine
            $existingFine = Fine::where('loan_id', $loan->id)->first();
            
            if (!$existingFine) {
                // Buat fine dengan status random
                $status = $index % 2 == 0 ? 'unpaid' : 'paid';
                
                Fine::create([
                    'loan_id' => $loan->id,
                    'user_id' => $loan->user_id,
                    'amount' => rand(10000, 100000),
                    'description' => 'Denda keterlambatan ' . rand(1, 10) . ' hari',
                    'late_days' => rand(1, 10),
                    'status' => $status,
                    'payment_date' => $status == 'paid' ? Carbon::now()->subDays(rand(1, 5)) : null,
                    'payment_method' => $status == 'paid' ? 'cash' : null,
                    'payment_reference' => $status == 'paid' ? 'REF-' . rand(1000, 9999) : null,
                ]);
                
                $this->command->info("Fine created for loan #{$loan->id}");
            }
        }

        $this->command->info('Fines seeded successfully! Total: ' . Fine::count());
    }
}