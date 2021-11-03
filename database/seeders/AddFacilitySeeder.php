<?php

namespace Database\Seeders;

use App\Models\District;
use App\Models\Facility;
use App\Models\FacilityType;
use App\Models\ReportingCircle;
use Illuminate\Database\Seeder;

class AddFacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $facilities = [
            // Insert facility in Data format
            // ['id' => 'PO15999999990 ', 'name' => ' 1CBPO ', 'district' => 'Central Delhi', 'pincode' => ' 900056 ', 'circle' => 'Delhi', 'type' => 'PO'],
            // ['id' => 'PO32999999991 ', 'name' => ' 2CBPO ', 'district' => 'KOLKATA', 'pincode' => ' 900099 ', 'circle' => 'West Bengal', 'type' => 'PO'],
            // ['id' => 'FP32571000000', 'name' => 'Foreign Post, Kolkata', 'district' => 'KOLKATA', 'pincode' => '700001', 'circle' => 'West Bengal', 'type' => 'FP']
        ];

        foreach ($facilities as $facility) {
            $facility_type = FacilityType::where('name', $facility['type'])->first();
            $district = District::where('name', 'ilike', $facility['district'])->first();
            $circle = ReportingCircle::where('name', $facility['circle'])->first();

            if (Facility::where('facility_code', $facility['id'])->exists()) {
                continue;
            }

            Facility::create([
                'facility_code' => $facility['id'],
                'name' => $facility['name'],
                'pincode' => $facility['pincode'],
                'facility_type_id' => $facility_type->id,
                'district_id' => $district->id,
                'reporting_circle_id' => $circle->id,
            ]);
        }
    }
}
