<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Models\Post;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function showLoginForm()
    {
        $allowedIds = [8, 9, 10, 11, 12, 13, 14, 15, 16, 17];

        $randomPosts = Post::with('user')
            ->whereIn('id', $allowedIds)
            ->where('type', 'image')
            ->whereNotNull('media_assets')
            ->inRandomOrder()
            ->limit(10)
            ->get();

        $backgroundImages = [];
        
        foreach ($randomPosts as $post) {
            if (!empty($post->media_urls) && is_array($post->media_urls)) {
                $randomImage = $post->media_urls[array_rand($post->media_urls)];
                $backgroundImages[] = [
                    'url' => asset($randomImage),
                    'post' => $post
                ];
            }
        }

        return view('auth.login', compact('backgroundImages'));
    }
}
