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
            '' => ''  
        ];

        foreach ($article_types as $key => $value) {
            ArticleType::create([
                'name' => $key,
                'description' => $value
            ]);
        }
    }
}
