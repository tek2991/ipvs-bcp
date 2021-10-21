<?php

namespace App\Http\Controllers;

use App\Models\Bag;
use App\Models\BagType;
use App\Models\Facility;
use Illuminate\Http\Request;
use App\Models\BagTransactionType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BagReceiveController extends Controller
{
    public function index(){
        
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();

        $bags_received = $active_set->bags()->paginate();

        $facilities = Facility::get();
        $bag_types = BagType::get();

        $bags = Bag::paginate();

        return view('bagReceive', compact('bags_received', 'bags', 'active_set', 'facilities', 'bag_types'));
    }

    public function store(Request $request){
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set_id = $current_facility->sets()->where('is_active', true)->firstOrFail()->id;

        $bag_transaction_type_id = BagTransactionType::where('name', 'RD')->get()->first()->id;

        $this->validate($request, [
            'from_facility_id' => 'required|exists:facilities,id',
            'bag_type_id' => 'required|exists:bag_types,id',
            // 'bag_no' => 'required|alpha_num|size:13|regex:^[a-zA-Z]{3}[0-9]{10}$^',
            'bag_no' => [
                'required', 'alpha_num', 'size:13', 'regex:^[a-zA-Z]{3}[0-9]{10}$^',
                Rule::unique('bags')->where(function ($query) use ($active_set_id) {
                    return $query->where('set_id', $active_set_id);
                })
            ],
        ]);

        Bag::create([
            'bag_no' => strtoupper($request->bag_no),
            'bag_type_id' => $request->bag_type_id,
            'from_facility_id' => $request->from_facility_id,
            'to_facility_id' => $current_facility->id,
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
