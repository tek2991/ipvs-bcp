<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class BagOpenController extends Controller
{
    public function index(){
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();

        return view('bagOpen', compact('active_set'));
    }

    public function scan(Request $request){
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->firstOrFail();

        $this->validate($request, [
            'bag_no' => [
                'required', 'alpha_num', 'size:13', 'regex:^[a-zA-Z]{3}[0-9]{10}$^',
                Rule::exists('bags')->where(function ($query) use ($active_set) {
                    return $query->where('set_id', $active_set->id);
                })
            ],
        ]);

        dd($request);
    }
}
