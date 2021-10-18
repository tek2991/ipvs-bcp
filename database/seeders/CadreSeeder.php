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
            'Asst Supdt POs',
            'Inspector Posts',
            'Sorting Assistant',
            'Multi Tasking Staff',
            'Postal Service GrP B',
            'Mail Guard',
            'SA LSG',
            'Canteen',
            'PA LSG',
            'SA HSG-II',
            'HSG II',
            'Daksevak',
        ];

        foreach($cadres as $cadre){
            Cadre::create([
                'name' => $cadre,
            ]);
        }
    }
}
