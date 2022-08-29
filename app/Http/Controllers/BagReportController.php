<?php

namespace App\Http\Controllers;

use App\Models\BagTransactionType;
use App\Models\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;

class BagReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $current_facility = $user->facility;
        $sets = $current_facility->sets()->orderBy('created_at', 'desc')->get();



        return view('bagReport', compact('sets'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $current_facility = $user->facility;

        $this->validate($request, [
            'set_id' => 'nullable|integer|exists:sets,id',
            'bag_report_type' => 'nullable|string|in:receive,close'
        ]);
        $bag_statuses = $request->bag_report_type == 'receive' ? ['RD', 'OP_SCAN', 'OP', 'DI'] : ['CL', 'DI_SCAN', 'DI'];
        $bag_status_ids = BagTransactionType::whereIn('name', $bag_statuses)->get()->modelKeys();
        $set = Set::find($request->set_id);
        $bags = $set->bags()->whereIn('bag_transaction_type_id', $bag_status_ids)->get();

        return view('pdf.bagReport', compact('bags', 'set', 'request', 'user', 'current_facility'));

        // $pdf = PDF::loadView('pdf.bagReport', compact('bags', 'set', 'request', 'user', 'current_facility'));
        // return $pdf->download('bag' . $request->bag_report_type . '_report' . '.pdf');

        // return view('pdf.bagReport', compact('bags', 'set', 'request', 'user', 'current_facility'));
    }
}
