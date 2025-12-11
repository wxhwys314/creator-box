<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display search results
     */
    public function index(Request $request)
    {
        $query = $request->get('q');
        $users = [];
        
        if ($query) {
            $users = User::where(function($q) use ($query) {
                        $q->where('name', 'like', '%' . $query . '%')
                        ->where('role', 'creator');
                    })
                    ->orWhere(function($q) use ($query) {
                        $q->where('creator_id', 'like', '%' . $query . '%')
                        ->where('role', 'creator');
                    })
                    ->with(['posts' => function($query) {
                        $query->published()
                            ->whereNotNull('cover')
                            ->orderBy('published_at', 'desc')
                            ->limit(3);
                    }])
                    ->orderBy('name')
                    ->paginate(10)
                    ->withQueryString();
        }

        return view('search', compact('users', 'query'));
    }
}