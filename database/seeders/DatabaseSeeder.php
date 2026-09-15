<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            DepartmentSeeder::class,
            PlaceSeeder::class,
            TypeSeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
        ]);
    }
}
