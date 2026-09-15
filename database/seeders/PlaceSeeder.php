<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PlaceSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('places')->insert([
            ['name' => 'Gudang A', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Gudang B', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lab 1', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lab 2', 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Ruangan Tools', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
