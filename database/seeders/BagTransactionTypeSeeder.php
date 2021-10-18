<?php

namespace Database\Seeders;

use App\Models\BagTransactionType;
use Illuminate\Database\Seeder;

class BagTransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bag_transaction_types = [
            '' => ''  
        ];

        foreach ($bag_transaction_types as $key => $value) {
            BagTransactionType::create([
                'name' => $key,
                'description' => $value
            ]);
        }
    }
}