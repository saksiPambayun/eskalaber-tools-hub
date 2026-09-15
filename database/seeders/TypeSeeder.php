<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TypeSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('types')->insert([
            ['name' => 'Elektronik', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Mekanik', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Hand Tools', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Power Tools', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Measurement', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
