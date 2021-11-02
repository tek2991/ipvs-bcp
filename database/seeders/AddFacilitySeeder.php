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
            ['id' => 'RM122500000001', 'name' => 'RMS GH Division', 'district' => 'KAMRUP METROPOLITAN', 'pincode' => '781001', 'circle' => 'Assam', 'type' => 'RM'],
            ['id' => 'PH12150000761', 'name' => 'Jorhat PH', 'district' => 'JORHAT', 'pincode' => '785001', 'circle' => 'Assam', 'type' => 'PH'],
        ];

        foreach ($facilities as $facility) {
            $facility_type = FacilityType::where('name', $facility['type'])->first();
            $district = District::where('name', 'ilike', $facility['district'])->first();
            $circle = ReportingCircle::where('name', $facility['circle'])->first();

            if(Facility::where('facility_code', $facility['id'])->exists()){
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
