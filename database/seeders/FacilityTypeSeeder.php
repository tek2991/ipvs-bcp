<?php

namespace Database\Seeders;

use App\Models\FacilityType;
use Illuminate\Database\Seeder;

class FacilityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $facility_types = [
            'PH' => 'Parcel Hub',
            'MO' => 'Mail Office',
            'SP' => 'Speed Post Hub',
            'EP' => 'Express Parcel Hub',
            'HO' => 'Head Office',
            'IC' => 'ICH Hub',
            'TM' => 'Transit Mail Office',
            'RL' => 'Return Letter Office',
            'SO' => 'Sub Post Office',
            'FP' => 'Foreign Post Office',
        ];

        foreach ($facility_types as $key => $value) {
            FacilityType::create([
                'name' => $key,
                'description' => $value
            ]);
        }
    }
}
