<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function follow(Request $request, $creatorId)
    {
        $creator = User::where('id', $creatorId)->where('role', 'creator')->first();

        if (Auth::id() === $creator->id) {
            return redirect()->back()->with('error', 'You cannot follow yourself.');
        }

        if (Auth::user()->isFollowing($creator->id)) {
            return redirect()->back()->with('info', 'You are already following this creator.');
        }

        Auth::user()->followings()->attach($creator->id);

        return redirect()->back()->with('success', 'You are now following ' . $creator->name);
    }
    
    public function unfollow(Request $request, $creatorId)
    {
        $creator = User::find($creatorId);

        Auth::user()->followings()->detach($creator->id);

        return redirect()->back()->with('success', 'You have unfollowed ' . $creator->name);
    }

    /**
     * Display a listing of the user's followed creators.
     */
    public function myFollowings()
    {
        $user = Auth::user();
        $followings = $user->followings()
                        ->with(['posts' => function($query) {
                            $query->published()
                                ->whereNotNull('cover')
                                ->orderBy('published_at', 'desc')
                                ->limit(3);
                        }])
                        ->orderBy('name')
                        ->paginate(10);

        return view('users.following', compact('user', 'followings'));
    }
}