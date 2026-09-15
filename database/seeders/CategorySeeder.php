<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('categories')->insert([
            ['name' => 'Drill', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Saw', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Wrench', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Screwdriver', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Measuring', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
