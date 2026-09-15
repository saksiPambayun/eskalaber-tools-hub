<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('users')->insert([
            [
                'name' => 'Super Admin',
                'email' => 'admin@eskalaber.com',
                'password' => Hash::make('password'),
                'role' => 'SUPERADMIN',
                'department_id' => 1,
                'phone' => '081234567890',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Toolsman',
                'email' => 'toolsman@eskalaber.com',
                'password' => Hash::make('password'),
                'role' => 'TOOLSMAN',
                'department_id' => 1,
                'phone' => '081234567891',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'User',
                'email' => 'user@eskalaber.com',
                'password' => Hash::make('password'),
                'role' => 'USER',
                'department_id' => 2,
                'phone' => '081234567892',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
