<?php

namespace App\Http\Controllers;

use App\Models\Bag;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class pdfReportController extends Controller
{
    public function manifest(Bag $bag){
        $pdf = PDF::loadView('pdf.manifest', ['bag' => $bag,]);
        return $pdf->download('manifest_' . $bag->bag_no . '.pdf');
    }
}
