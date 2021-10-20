<?php

namespace App\Http\Controllers;

use App\Models\Set;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetController extends Controller
{
    public function index()
    {
        $currently_active = Auth::user()->facility->sets->where('is_active', true);
        $previously_active = Auth::user()->facility->sets->where('is_active', false);

        return view('set', compact('currently_active', 'previously_active'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = Set::where('facility_id', $current_facility->id)->where('is_active', true)->get();

        if (count($active_set) > 0) {
            return redirect()
                ->back()
                ->with('error', 'Please close open set, started on: ' . $active_set->first()->created_at->toDayDateTimeString());
        }

        Set::create([
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'facility_id' => $current_facility->id,
            'is_active' => true,
        ]);

        return redirect()
        ->back()
        ->with('success', 'Set started');
    }

    public function update(){
        $user = Auth::user();
        $current_facility = $user->facility;
        $active_set = $current_facility->sets()->where('is_active', true)->get();

        if (count($active_set) == 0) {
            return redirect()
                ->back()
                ->with('error', 'No active set');
        }

        $active_set->first()->update([
            'is_active' => false,
        ]);

        return redirect()
        ->back()
        ->with('success', 'Set closed');
    }
}
