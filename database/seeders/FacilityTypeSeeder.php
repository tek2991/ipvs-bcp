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
            'AO' => 'Accounts Office',
            'BN' => 'BNPL',
            'BO' => 'Branch Office',
            'CB' => 'NA',
            'CS' => 'NA',
            'DB' => 'District Bag Office',
            'DI' => 'NA',
            'EP' => 'Express Parcel Hub',
            'HO' => 'Head Office',
            'HR' => 'Head Record Office',
            'IC' => 'ICH Hub',
            'LP' => 'Logistics Post',
            'MM' => 'Mail Motor Service',
            'MO' => 'Mail Office',
            'PC' => 'NA',
            'PH' => 'Parcel Hub',
            'PO' => 'Post Office',
            'PS' => 'NA',
            'RM' => 'RMS Dic Office',
            'SP' => 'Speed Post Hub',
            'SR' => 'Sub Record Office',
            'SV' => 'Sub Division',
            'TC' => 'Transhipment Center',
            'TM' => 'Transit Mail Office',
            'RL' => 'Return Letter Office',
            'EX' => 'Foreign Exchange',
            'SO' => 'Sub Post Office',
            'FP' => 'Foreign Post Office',
            'CE' => 'NA',
        ];

        foreach ($facility_types as $key => $value) {
            FacilityType::create([
                'name' => $key,
                'description' => $value
            ]);
        }
    }
}
