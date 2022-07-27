<?php

namespace Database\Seeders;

use App\Models\BagType;
use Illuminate\Database\Seeder;

class BagTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $bag_types = [
            'PP' => 'Parcel Bag',  
            'RP' => 'Register Bag',  
            'SP' => 'Speed Post Bag',  
            'EP' => 'Express Parcel Bag',
        ];

        foreach ($bag_types as $key => $value) {
            BagType::create([
                'name' => $key,
                'description' => $value
            ]);
        }
    }
}
