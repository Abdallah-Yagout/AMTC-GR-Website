<?php

namespace App\Http\Controllers;

use App\Models\participants;
use App\Models\Tournament;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ParticipantsController extends Controller
{
    public function index()
    {
        $data['tournament']=Tournament::active()->first();
        $data['participants']=participants::where('tournament_id',$data['tournament']->id)->get();
        return view('participants.index',$data);
    }

    public function apply($id)
    {

        return view('tournament.apply', ['tournamentId' => $id]);

    }

    public function submit(Request $request)
    {

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'gender' => ['required', Rule::in(['Male', 'Female'])],
                'phone' => 'required|string|max:20',
                'email' => 'required|email|unique:participants,email',
                'city' => 'required|string|max:255',
                'tournamentId' => 'required|exists:tournaments,id', // Make sure tournament exists
            ]);

            // Create participant with all required fields
            participants::create([
                'user_id' => auth()->id(),
                'tournament_id' => $validated['tournamentId'],
                'location' => $validated['city'],
                // Add any other fields you need to save
            ]);
        }
        catch (\Exception $e) {
            dd($e->getMessage());
        }


        return redirect()->back()->with('success', 'Application submitted successfully!');
    }
}
