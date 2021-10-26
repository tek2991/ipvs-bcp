<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ArticleExport implements FromCollection, WithHeadings, WithMapping
{
    protected $articles;
    protected $status;
    function __construct($articles, $status)
    {
        $this->articles = $articles;
        $this->status = $status;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->articles;   
    }

    public function headings(): array
    {
        return [
            'Bag Barcode',
            'Bag received from/ closed To',
            'Bag status',
            'Bag Type',
            'Article Number',
            'Article Type',
            'Insured'
        ];
    }

    public function map($article): array
    {
        return [
            $article->bag->bag_no,
            $article->bag->fromFacility->facility_code,
            $this->status,
            $article->bag->bagType->name,
            $article->article_no,
            $article->articleType->name,
            $article->is_insured == true ? 'Y' : 'N',
        ];
    }
}
