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
            'RD' => 'Bag Receipt',  
            'OP' => 'Bag Open',  
            'OP_SCAN' => 'Bag Open Scan',  
            'CL' => 'Bag Close',  
            'CL_SCAN' => 'Bag Close Scan',  
            'DI' => 'Bag Dispatch',  
            'DI_SCAN' => 'Bag Dispatch Scan',  
        ];

        foreach ($bag_transaction_types as $key => $value) {
            BagTransactionType::create([
                'name' => $key,
                'description' => $value
            ]);
        }
    }
}
