<?php

namespace Database\Seeders;

use App\Models\Cadre;
use Illuminate\Database\Seeder;

class CadreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cadres =  [
            'Sorting Assistant',
            'Multi Tasking Staff',
            'Mail Guard',
            'GDS',
        ];

        foreach($cadres as $cadre){
            Cadre::create([
                'name' => $cadre,
            ]);
        }
    }
}
