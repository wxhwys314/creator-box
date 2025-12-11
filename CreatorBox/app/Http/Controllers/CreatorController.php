<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Sponsorship;

class CreatorController extends Controller
{
    // Show creator profile
    public function showProfile($creatorId)
    {
        // use creator_id to find the user
        $creator = User::where('creator_id', $creatorId)->where('role', 'creator')->firstOrFail();

        // get the creator's published posts
        $posts = $creator->posts()
            ->where('status', 'published')
            ->withCount(['likes', 'comments'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // get the creator's subscription plans
        $plans = $creator->plans()->where('is_active', true)->orderBy('created_at', 'desc')->get();

        // check if the logged-in user is the owner of this profile
        $isOwner = false;
        $isLoggedIn = auth()->check();
        
        if ($isLoggedIn) {
            $isOwner = auth()->id() === $creator->id;
        }

        return view('creators.profile', compact(
            'creator', 
            'posts', 
            'plans',
            'isOwner',
            'isLoggedIn'
        ));
    }

    ////======= Creator Registration Methods =======////
    
    // Show creator registration form
    public function showRegister()
    {
        if (Auth::user()->isCreator()) {
            return redirect('/')->with('info', 'You are already a creator!');
        }

        return view('creators.register');
    }

    // Handle creator registration
    public function creatorRegister(Request $request)
    {
        $request->validate([
            'creator_id' => [
                'required',
                'string',
                'max:255',
                'unique:users,creator_id',
                'regex:/^[a-z]+$/'  // only lowercase letters allowed
            ]
        ], [
            'creator_id.regex' => 'Creator ID can only contain lowercase letters (a-z). No numbers, spaces, or special characters allowed.',
            'creator_id.unique' => 'This Creator ID is already taken. Please choose another one.',
        ]);

        // upgrade user role to creator
        Auth::user()->update([
            'role' => 'creator',
            'creator_id' => $request->creator_id
        ]);

        return redirect('/')->with('success', 'Congratulations! You are now a creator.');
    }

    ////======= Creator " My Fans and Supporters" Methods =======////

    /**
     * Display fans and supporters of the creator
     */
    public function relationship(Request $request)
    {
        $creator = Auth::user();
        
        // Get query parameters
        $status = $request->get('status', 'all');
        $planId = $request->get('planId');
        
        // Get creator's active plans for filter
        $plans = $creator->plans()->where('is_active', true)->get();
        
        $data = [
            'followers' => collect(),
            'supporters' => collect(),
            'selected_status' => $status,
            'selected_plan_id' => $planId,
            'plans' => $plans,
        ];
        
        // Get followers
        if (in_array($status, ['all', 'follower'])) {
            $data['followers'] = $creator->followers()
                ->withCount(['sponsorships' => function($query) use ($creator) {
                    $query->where('creator_id', $creator->id)
                          ->where('status', 'active');
                }])
                ->get();
        }
        
        // Get supporters
        if (in_array($status, ['all', 'supporter'])) {
            $supportersQuery = Sponsorship::where('creator_id', $creator->id)
                ->with(['supporter', 'plan'])
                ->where(function($query) {
                    $query->where('status', 'active')
                          ->orWhere(function($q) {
                              $q->where('status', 'cancelled')
                                ->where('expires_at', '>', now());
                          });
                });
            
            // Filter by specific plan
            if ($planId) {
                $supportersQuery->where('plan_id', $planId);
            }
            
            $sponsorships = $supportersQuery->latest()->get();
            $data['supporters'] = $sponsorships->groupBy('supporter_id')->map(function($userSponsorships) {
                return [
                    'user' => $userSponsorships->first()->supporter,
                    'sponsorships' => $userSponsorships,
                    'total_monthly' => $userSponsorships->sum('monthly_amount'),
                ];
            });
        }
        
        return view('creators.relationship', $data);
    }

    ////======= Find creator Methods =======////

    /**
     * Display some creators for users to find
     */
    public function findCreators()
    {
        $creators = User::where('role', 'creator')
            ->with(['posts' => function($query) {
                $query->where('status', 'published')
                    ->whereNotNull('cover')
                    ->orderBy('created_at', 'desc')
                    ->limit(3);
            }])
            ->paginate(10);

        return view('creators.find', compact('creators'));
    }
}
