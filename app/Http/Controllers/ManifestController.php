<?php

namespace App\Http\Controllers;

use App\Models\Bag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\BagTransactionType;
use Illuminate\Support\Facades\Auth;

class ManifestController extends Controller
{
    public function index(Request $request){
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();

        $bag_statuses = BagTransactionType::whereIn('name', ['CL', 'DI'])->get()->modelKeys();

        $this->validate($request, [
            'bag_no' => ['nullable',
                Rule::exists('bags')->where(function($query) use($bag_statuses){
                    return $query->whereIn('bag_transaction_type_id', $bag_statuses);
                })
            ]
        ]);

        $bags = Bag::where('bag_no', $request->bag_no)->whereIn('bag_transaction_type_id', $bag_statuses);

        return view('manifest', compact('active_set', 'bags', 'request'));
    }
}
