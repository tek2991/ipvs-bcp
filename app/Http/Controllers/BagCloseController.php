<?php

namespace App\Http\Controllers;

use App\Models\Bag;
use App\Models\BagType;
use App\Models\Facility;
use Illuminate\Http\Request;
use App\Rules\BagClosingRule;
use Illuminate\Validation\Rule;
use App\Models\BagTransactionType;
use Illuminate\Support\Facades\Auth;

class BagCloseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();
        $facilities = Facility::get();
        $bag_types = BagType::get();

        $bag_statuses = BagTransactionType::whereIn('name', ['CL_SCAN'])->get()->modelKeys();
        $close_bags = Bag::where('set_id', $active_set->id)->whereIn('bag_transaction_type_id', $bag_statuses)->paginate();

        return view('bagClose', compact('active_set', 'facilities', 'bag_types', 'close_bags'));
    }

    public function bagScan(Request $request){
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set_id = $current_facility->sets()->where('is_active', true)->firstOrFail()->id;

        $bag_transaction_type_id = BagTransactionType::where('name', 'CL_SCAN')->get()->first()->id;

        $this->validate($request, [
            'to_facility_id' => 'bail|required|exists:facilities,id',
            'bag_type_id' => 'bail|required|exists:bag_types,id',
            'bag_no' => [
                'bail', 'required', 'alpha_num', 'size:13', 'regex:^[a-zA-Z]{2}[sS]{1}[0-9]{10}$^',
                Rule::unique('bags')->where(function ($query) use ($active_set_id) {
                    return $query->where('set_id', $active_set_id);
                }),
                new BagClosingRule($active_set_id),
            ],
        ]);

        Bag::create([
            'bag_no' => strtoupper($request->bag_no),
            'bag_type_id' => $request->bag_type_id,
            'from_facility_id' => $current_facility->id,
            'to_facility_id' => $request->to_facility_id,
            'bag_transaction_type_id' => $bag_transaction_type_id,
            'set_id' => $active_set_id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        return redirect()
        ->back()
        ->withInput();
    }
}