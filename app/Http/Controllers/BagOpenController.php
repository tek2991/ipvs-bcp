<?php

namespace App\Http\Controllers;

use App\Models\Bag;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\BagTransactionType;
use Illuminate\Support\Facades\Auth;

class BagOpenController extends Controller
{
    public function index(){
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();

        $bag_statuses = BagTransactionType::whereIn('name', ['OP_SCAN'])->get()->modelKeys();

        $open_bags = Bag::where('set_id', $active_set->id)->whereIn('bag_transaction_type_id', $bag_statuses)->paginate();

        return view('bagOpenBagScan', compact('active_set', 'open_bags'));
    }

    public function bagScan(Request $request){
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();

        $this->validate($request, [
            'bag_no' => [
                'required', 'alpha_num', 'size:13', 'regex:^[a-zA-Z]{3}[0-9]{10}$^',
                Rule::exists('bags')->where(function ($query) use ($active_set) {
                    $bag_statuses = BagTransactionType::whereIn('name', ['RD', 'OP_SCAN'])->get()->modelKeys();
                    return $query->where('set_id', $active_set->id)->whereIn('bag_transaction_type_id', $bag_statuses);
                })
            ],
        ]);

        $bag_transaction_type_id = BagTransactionType::where('name', 'OP_SCAN')->get()->first()->id;

        $bag = Bag::where('bag_no', $request->bag_no)->first();

        if($bag->bag_transaction_type_id = $bag_transaction_type_id && $bag->updated_by != $user->id){
            return redirect()
            ->back()
            ->with('error', 'Bag is scaned by another user: '. $bag->updator->name);
        }

        $bag->update([
            'bag_transaction_type_id' => $bag_transaction_type_id,
            'updated_by' => $user->id,
        ]);

        return view('bagOpenArticleScan', compact('active_set', 'request'));
    }

    public function articleScan(Request $request){
        dd($request);
    }
}
