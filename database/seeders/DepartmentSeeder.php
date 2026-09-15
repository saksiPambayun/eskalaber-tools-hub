<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;  // <- Pastikan ini ada

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = ['IT', 'HRD', 'Finance', 'Marketing', 'Operations'];

        foreach ($departments as $dept) {
            // Cara 1: Menggunakan fully qualified namespace
            \App\Models\Department::create(['name' => $dept]);

            // Atau cara 2: Jika use statement sudah benar
            // Department::create(['name' => $dept]);
        }
    }
}
