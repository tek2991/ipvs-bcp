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
            'OP_SCAN' => 'Bag Open Scan',  
            'OP' => 'Bag Open',  
            'CL_SCAN' => 'Bag Close Scan',  
            'CL' => 'Bag Close',  
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
