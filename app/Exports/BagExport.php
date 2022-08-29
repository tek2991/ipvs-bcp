<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class BagExport implements FromCollection, WithHeadings, WithMapping
{
    protected $bags;
    protected $status;
    protected $report_type;
    function __construct($bags, $status, $report_type)
    {
        $this->bags = $bags;
        $this->status = $status;
        $this->report_type = $report_type;
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
        if($this->report_type == 'bag_receive'){
            return [
                $bag->bag_no,
                $bag->fromFacility->facility_code,
                $bag->bagType->name,
                $this->status,
                $bag->weight
            ];
        }else{
            return [
                $bag->bag_no,
                $bag->toFacility->facility_code,
                $bag->bagType->name,
                $this->status,
                $bag->weight
            ];
        }
        
        // return [
        //     $bag->bag_no,
        //     $bag->fromFacility->facility_code,
        //     $bag->bagType->name,
        //     $this->status,
        //     '1',
        // ];
    }
}
