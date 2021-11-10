<?php

namespace Database\Seeders;

use App\Models\Facility;
use App\Models\FacilityMapping;
use Illuminate\Database\Seeder;

class MapAllFacilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $facilities = Facility::get()->modelKeys();
        $facility_code = 'PH12250000761';
        $base_facility_id = Facility::where('facility_code', $facility_code)->first()->id;

        foreach($facilities as $facility){
            FacilityMapping::create([
                'base_facility_id' => $base_facility_id,
                'mapped_facility_id' => $facility,
            ]);
        }
    }
}
