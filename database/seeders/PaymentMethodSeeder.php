<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['name' => 'QRIS', 'type' => 'qris', 'account_number' => null, 'account_name' => 'Eskalaber Tools Hub', 'is_active' => true],
            ['name' => 'BCA', 'type' => 'bank', 'account_number' => '1234567890', 'account_name' => 'Eskalaber Tools Hub', 'is_active' => true],
            ['name' => 'BRI', 'type' => 'bank', 'account_number' => '0987654321', 'account_name' => 'Eskalaber Tools Hub', 'is_active' => true],
            ['name' => 'Mandiri', 'type' => 'bank', 'account_number' => '1122334455', 'account_name' => 'Eskalaber Tools Hub', 'is_active' => true],
        ];

        foreach ($defaults as $data) {
            PaymentMethod::firstOrCreate(['name' => $data['name']], $data);
        }
    }
}
