<?php

namespace App\Http\Controllers;

use App\Models\Bag;
use App\Models\BagType;
use App\Models\Facility;
use Illuminate\Http\Request;
use App\Rules\BagDispatchRule;
use Illuminate\Validation\Rule;
use App\Models\BagTransactionType;
use Illuminate\Support\Facades\Auth;

class BagDispatchController extends Controller
{
    public function index()
    {

        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();

        $bag_statuses = BagTransactionType::whereIn('name', ['DI', 'DI_SCAN'])->get()->modelKeys();
        $bags_dispatched = $active_set->bags()->whereIn('bag_transaction_type_id', $bag_statuses)->paginate();

        $facility_ids = $current_facility->mappedFacilities()->get('mapped_facility_id')->pluck('mapped_facility_id')->toArray();
        $facilities = Facility::whereIn('id', $facility_ids)->orderBy('name')->get();
        $bag_types = BagType::get();

        $bags = Bag::paginate();

        return view('bagDispatch', compact('bags_dispatched', 'bags', 'active_set', 'facilities', 'bag_types'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set_id = $current_facility->sets()->where('is_active', true)->firstOrFail()->id;
        $bag_transaction_type_id = BagTransactionType::where('name', 'DI')->get()->first()->id;

        $this->validate($request, [
            'to_facility_id' => 'bail|required|exists:facilities,id',
            'bag_type_id' => 'bail|required|exists:bag_types,id',
            // 'bag_no' => 'bail', 'required', 'alpha_num', 'size:13', 'regex:^[a-zA-Z]{3}[0-9]{10}$^',
            'bag_no' => [
                'bail', 'required', 'alpha_num', 'size:13', 'regex:^[a-zA-Z]{3}[0-9]{10}$^',
                new BagDispatchRule($active_set_id),
            ],
        ]);

        // updateOrCreate bag

        Bag::updateOrCreate([
            'bag_no' => strtoupper($request->bag_no),
            'set_id' => $active_set_id,
        ], [
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
