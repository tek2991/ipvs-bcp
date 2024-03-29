<?php

namespace App\Http\Controllers;

use App\Models\Bag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\BagTransactionType;
use Illuminate\Support\Facades\Auth;
use Storage;

class ManifestController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->get();

        $bag_statuses = BagTransactionType::whereIn('name', ['CL', 'DI'])->get()->modelKeys();

        $this->validate($request, [
            'bag_no' => ['nullable',
                Rule::exists('bags')->where(function($query) use($bag_statuses){
                    return $query->whereIn('bag_transaction_type_id', $bag_statuses);
                })
            ]
        ]);

        $bags = Bag::where('bag_no', $request->bag_no)->whereIn('bag_transaction_type_id', $bag_statuses)->paginate();

        return view('manifest', compact('active_set', 'bags', 'request'));
    }

    public function downloadExcel(Bag $bag){
        $export = $bag->export;
        $file_path = $export->file_path;

        // let user download the file from storage folder
        return Storage::download($file_path);
    }
}
