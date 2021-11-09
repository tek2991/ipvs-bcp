<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class EnableFacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $facilities = [
            // Insert facility in Data format
            ['id' => 'PH12250000761']
        ];

        foreach ($facilities as $facility) {
            $selectedFacility = Facility::where('facility_code', $facility['id'])->firstOrFail();

            $selectedFacility->update([
                'is_active' => true
            ]);
        }
    }
}
