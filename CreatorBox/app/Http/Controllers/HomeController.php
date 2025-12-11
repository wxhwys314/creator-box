<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Post;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $posts = [];

        $randomCreators = User::where('role', 'creator')
            ->where('id', '!=', Auth::id())
            ->with(['posts' => function($query) {
                $query->where('status', 'published')
                    ->whereNotNull('cover')
                    ->orderBy('created_at', 'desc')
                    ->limit(3);
            }])
            ->inRandomOrder()
            ->limit(6)
            ->get();

        $usersCount = User::count();
        $postsCount = Post::count();
        $creatorsCount = User::where('role', 'creator')->count();

        // if logged in, and role is creator, return posts self created by the creator
        if (auth()->check() && auth()->user()->role === 'creator') {
            $posts = Post::where('user_id', auth()->id())
                ->where('status', 'published')
                ->withCount(['likes', 'comments'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        return view('home', compact('randomCreators', 'usersCount', 'postsCount', 'creatorsCount', 'posts'));
    }
}
