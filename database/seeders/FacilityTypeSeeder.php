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
            '' => ''  
        ];

        foreach ($facility_types as $key => $value) {
            FacilityType::create([
                'name' => $key,
                'description' => $value
            ]);
        }
    }
}
