<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class BagExport implements FromCollection, WithHeadings, WithMapping
{
    protected $bags;
    protected $status;
    function __construct($bags, $status)
    {
        $this->bags = $bags;
        $this->status = $status;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->bags;   
    }

    public function headings(): array
    {
        return [
            'Bag Barcode',
            'Bag received from/ closed To',
            'Bag Type',
            'Bag status',
            'Bag Weight(In Kg)'
        ];
    }

    public function map($bag): array
    {
        return [
            $bag->bag_no,
            $bag->fromFacility->facility_code,
            $bag->bagType->name,
            $this->status,
            '1',
        ];
    }
}
