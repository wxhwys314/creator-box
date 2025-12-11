<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\UserController;
use App\Http\Controllers\CreatorController;

use App\Http\Controllers\FollowController;
use App\Http\Controllers\SearchController;

use App\Http\Controllers\PostController;

use App\Http\Controllers\PlanController;
use App\Http\Controllers\SponsorshipController;

use App\Http\Controllers\DailyController;

Auth::routes();

// --------------------
// Authenticated routes (logged-in users)
// --------------------
Route::middleware('auth')->group(function () {
    // User settings
    Route::get('/settings', [UserController::class, 'showSettings'])->name('users.settings');
    Route::put('/settings', [UserController::class, 'updateSettings'])->name('users.settings.update');
    Route::delete('/settings/avatar', [UserController::class, 'deleteAvatar'])->name('users.settings.avatar.delete');
    Route::delete('/settings/cover', [UserController::class, 'deleteCover'])->name('users.settings.cover.delete');
    Route::delete('/users', [UserController::class, 'destroy'])->name('users.settings.destroy');

    // Follow / unfollow creators
    Route::post('/creators/{creator}/follow', [FollowController::class, 'follow'])->name('follow.follow');
    Route::delete('/creators/{creator}/unfollow', [FollowController::class, 'unfollow'])->name('follow.unfollow');

    // My followed creators
    Route::get('/following', [FollowController::class, 'myFollowings'])->name('follow.index');

    // Comments and likes
    Route::post('/posts/{post}/comments', [PostController::class, 'storeComment'])->name('posts.comments.store');
    Route::delete('/comments/{comment}', [PostController::class, 'destroyComment'])->name('posts.comments.destroy');
    Route::post('/posts/{post}/like', [PostController::class, 'toggleLike'])->name('posts.like.toggle');

    // Sponsorships
    Route::get('/my-sponsorships', [SponsorshipController::class, 'index'])->name('sponsorships.index');
    Route::post('/sponsorships', [SponsorshipController::class, 'store'])->name('sponsorships.store');
    Route::delete('/sponsorships/{sponsorship}', [SponsorshipController::class, 'cancel'])->name('sponsorships.cancel');

    // Check in
    Route::get('/daily', [DailyController::class, 'index'])->name('daily.index');
    Route::post('/daily', [DailyController::class, 'store'])->name('daily.store');
});

// --------------------
// Fans-only routes (role: fan)
// --------------------
Route::middleware(['auth', 'role:fan'])->group(function () {
    // Register as a creator
    Route::get('/creator-register', [CreatorController::class, 'showRegister'])->name('creators.register');
    Route::post('/creator-register', [CreatorController::class, 'creatorRegister'])->name('creators.register.store');
});

// --------------------
// Creators-only routes (role: creator)
// --------------------
Route::middleware(['auth', 'role:creator'])->group(function () {
    // Manage posts
    Route::resource('/posts', PostController::class);

    // Manage sponsorship plans
    Route::resource('/plans', PlanController::class)->except(['show']);

    // View fans and supporters relationship
    Route::get('/relationship', [CreatorController::class, 'relationship'])->name('creators.relationship');
});

// --------------------
// Public routes
// --------------------
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public creator profile
Route::get('/@{creator_id}', [CreatorController::class, 'showProfile'])->name('creators.profile');

// Search creators
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

// Public posts
Route::get('public/posts/{post}', [PostController::class, 'publicShow'])->name('public.posts.show');

// Public sponsorship plan details
Route::get('plans/{plan}', [PlanController::class, 'show'])->name('plans.show');

// Find creators
Route::get('/creators/find', [CreatorController::class, 'findCreators'])->name('creators.find');