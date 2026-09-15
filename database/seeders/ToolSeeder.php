<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tool;
use App\Models\Category;
use App\Models\Type;
use App\Models\Place;

class ToolSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil category, type, place yang sudah ada
        $categories = Category::all();
        $types = Type::all();
        $places = Place::all();

        // Jika belum ada, buat dummy
        if ($categories->isEmpty()) {
            $categories = Category::factory()->count(3)->create();
        }
        if ($types->isEmpty()) {
            $types = Type::factory()->count(3)->create();
        }
        if ($places->isEmpty()) {
            $places = Place::factory()->count(3)->create();
        }

        $tools = [
            ['name' => 'Bor Listrik', 'code' => 'TL001', 'stock' => 5],
            ['name' => 'Gergaji Kayu', 'code' => 'TL002', 'stock' => 3],
            ['name' => 'Obeng Set', 'code' => 'TL003', 'stock' => 10],
            ['name' => 'Kunci Inggris', 'code' => 'TL004', 'stock' => 7],
            ['name' => 'Tang Kombinasi', 'code' => 'TL005', 'stock' => 4],
            ['name' => 'Mesin Gerinda', 'code' => 'TL006', 'stock' => 2],
            ['name' => 'Meteran', 'code' => 'TL007', 'stock' => 8],
            ['name' => 'Palu', 'code' => 'TL008', 'stock' => 6],
        ];

        foreach ($tools as $toolData) {
            Tool::create([
                'name' => $toolData['name'],
                'code' => $toolData['code'],
                'description' => 'Alat ' . $toolData['name'],
                'category_id' => $categories->random()->id,
                'type_id' => $types->random()->id,
                'place_id' => $places->random()->id,
                'stock' => $toolData['stock'],
                'status' => 'available',
            ]);
        }

        $this->command->info('Tools seeded successfully!');
    }
}