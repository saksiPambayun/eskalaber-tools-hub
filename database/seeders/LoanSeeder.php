<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Loan;
use App\Models\User;
use App\Models\Tool;
use Carbon\Carbon;

class LoanSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'USER')->get();
        $tools = Tool::all();

        foreach ($users as $user) {
            Loan::create([
                'user_id' => $user->id,
                'tool_id' => $tools->random()->id,
                'loan_date' => Carbon::now()->subDays(5),
                'return_date' => Carbon::now()->addDays(3),
                'status' => 'pending',
                'notes' => 'Peminjaman untuk project',
            ]);
        }
    }
}