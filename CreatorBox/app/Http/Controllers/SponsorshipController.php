<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Sponsorship;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SponsorshipController extends Controller
{
    /**
     * Display user's active sponsorships
     */
    public function index()
    {
        $sponsorships = Auth::user()->sponsorships()
            ->with(['plan.creator'])
            ->where(function($query) {
                $query->where('status', 'active')
                      ->orWhere(function($q) {
                          $q->where('status', 'cancelled')
                            ->where('expires_at', '>', now());
                      });
            })
            ->latest()
            ->get();

        return view('users.sponsorship', compact('sponsorships'));
    }

    /**
     * Create a new sponsorship
     */
    public function store(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:plans,id'
        ]);

        $plan = Plan::findOrFail($request->plan_id);

        // Check if user is already sponsoring this plan
        $existingSponsorship = Auth::user()->sponsorships()
            ->where('plan_id', $plan->id)
            ->where(function($query) {
                $query->where('status', 'active')
                      ->orWhere(function($q) {
                          $q->where('status', 'cancelled')
                            ->where('expires_at', '>', now());
                      });
            })
            ->first();

        if ($existingSponsorship) {
            return redirect()->back()->with('error', 'You are already sponsoring this creator!');
        }

        // Check if user has enough coins
        if (Auth::user()->coin_balance < $plan->monthly_amount) {
            return redirect()->back()->with('error', 'Insufficient coins. Please top up your balance.');
        }

        // Deduct coins from user
        Auth::user()->decrement('coin_balance', $plan->monthly_amount);

        // Add coins to creator
        $plan->creator->increment('coin_balance', $plan->monthly_amount);

        // Create sponsorship
        Sponsorship::create([
            'supporter_id' => Auth::id(),
            'plan_id' => $plan->id,
            'creator_id' => $plan->creator_id,
            'monthly_amount' => $plan->monthly_amount,
            'expires_at' => Sponsorship::calculateExpiryDate(),
        ]);

        return redirect()->route('sponsorships.index')->with('success', 'Successfully started sponsoring ' . $plan->creator->name . '!');
    }

    /**
     * Cancel a sponsorship
     */
    public function cancel(Sponsorship $sponsorship)
    {
        // Authorization check - user can only cancel their own sponsorships
        if ($sponsorship->supporter_id !== Auth::id()) {
            abort(403);
        }

        // Only active sponsorships can be cancelled
        if (!$sponsorship->isActive()) {
            return redirect()->back()->with('error', 'This sponsorship is already cancelled.');
        }

        $sponsorship->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            // expires_at remains the same - user keeps access until end of month
        ]);

        return redirect()->route('sponsorships.index')->with('success', 'Sponsorship cancelled. You will retain access until ' . $sponsorship->expires_at->format('M j, Y'));
    }
}