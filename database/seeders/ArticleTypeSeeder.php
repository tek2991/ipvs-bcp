<?php

namespace Database\Seeders;

use App\Models\ArticleType;
use Illuminate\Database\Seeder;

class ArticleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $article_types = [
            'LETTER' => 'Registered Letter (lncl. Insured and VP)',
            'PARCEL' => 'Registered Parcel (lncl. Insured and VP)',
            'SP_INLAND' => 'Speed Post Letter',  
            'SP_INLAND_PARCEL' => 'Speed Post Parcel',
            'BUSINESS_PARCEL' => 'Business Parcel',
            'FGN-SP-DOCUMENT' => 'International EMS Document',
            'FGN-SP-MERCHANDISE' => 'International EMS Merchandise',
            'FGN-LETTER' => 'International Registered Letter',
            'FGN AIR PARCEL' => 'International Registered Parcel',
        ];

        foreach ($article_types as $key => $value) {
            ArticleType::create([
                'name' => $key,
                'description' => $value
            ]);
        }
    }
}
