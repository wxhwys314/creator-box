<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyController extends Controller
{
    // Display the check-in page
    public function index()
    {
        $user = Auth::user();
        $hasCheckedInToday = $user->hasCheckedInToday();
        
        return view('daily.index', compact('hasCheckedInToday'));
    }
    
    // Perform the check-in
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if ($user->hasCheckedInToday()) {
            return back()->with('error', 'You have already checked in today!');
        }
        
        $user->checkin();
        
        return back()->with('success', 'Daily check-in successful! +1 coin');
    }
}