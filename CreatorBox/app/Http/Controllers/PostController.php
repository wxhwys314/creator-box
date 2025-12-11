<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');
        $query = auth()->user()->posts()->withCount(['comments', 'likes']);
        
        if ($type !== 'all') {
            $query->byType($type);
        }

        $posts = $query->latest()
                      ->paginate(12)
                      //todo:
                      ->withQueryString();

        return view('posts.index', compact('posts', 'type'));
    }

    public function create()
    {
        $creatorPlans = Auth::user()->plans()
            ->where('is_active', true)
            ->orderBy('monthly_amount', 'asc')
            ->get();

        return view('posts.create', compact('creatorPlans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:blog,image,file',
            'title' => 'required|string|max:255',
            'content_text' => 'nullable|string',
            'cover' => 'nullable|image|max:2048',
            'visibility' => 'required|in:all,supporters',
            'supporter_min_amount' => 'required_if:visibility,supporters|integer|min:0',
            'comment_permission' => 'required|in:all,supporters,none',
            'status' => 'required|in:draft,published',
            'media_images' => 'required_if:type,image|array',
            'media_images.*' => 'image|max:10240',
            'media_files' => 'required_if:type,file|array',
            'media_files.*' => 'file|max:307200',
        ]);

        // If visibility is all, reset supporter amount
        if ($validated['visibility'] === 'all') {
            $validated['supporter_min_amount'] = 0;
        } else {
            // Validate that the selected amount matches one of creator's plans
            $creatorPlanAmounts = Auth::user()->plans()
                ->where('is_active', true)
                //todo:
                ->pluck('monthly_amount')
                ->toArray();

            if (!in_array((int)$validated['supporter_min_amount'], $creatorPlanAmounts)) {
                return redirect()->back()
                    ->with('error', 'Invalid support amount selected.')
                    ->withInput();
            }
        }

        // if visibility or comment_permission is set to supporters, ensure user has at least one active plan
        $creatorPlansCount = Auth::user()->plans()->where('is_active', true)->count();
        
        if ($creatorPlansCount === 0) {
            if ($validated['visibility'] === 'supporters') {
                return redirect()->back()
                    ->with('error', 'You need to create at least one support plan to set visibility as "Supporters Only".')
                    ->withInput();
            }
            
            if ($validated['comment_permission'] === 'supporters') {
                return redirect()->back()
                    ->with('error', 'You need to create at least one support plan to set comment permission as "Supporters Only".')
                    ->withInput();
            }
        }

        // Extra validation: enforce comment_permission based on visibility
        if ($validated['visibility'] === 'all' && !in_array($validated['comment_permission'], ['all', 'none'])) {
            return redirect()->back()
                ->with('error', 'Invalid comment permission for "All Users" visibility.')
                ->withInput();
        }

        if ($validated['visibility'] === 'supporters' && !in_array($validated['comment_permission'], ['supporters', 'none'])) {
            return redirect()->back()
                ->with('error', 'Invalid comment permission for "Supporters Only" visibility.')
                ->withInput();
        }

        $postData = [
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'title' => $validated['title'],
            'content_text' => $validated['content_text'],
            'visibility' => $validated['visibility'],
            'supporter_min_amount' => $validated['supporter_min_amount'] ?? 0,
            'comment_permission' => $validated['comment_permission'],
            'status' => $validated['status'],
        ];

        // handle cover
        if ($request->hasFile('cover')) {
            $postData['cover'] = $request->file('cover')->store('posts/covers', 'public');
        }

        // handle media
        $mediaAssets = [];
        
        if ($validated['type'] === 'image' && $request->hasFile('media_images')) {
            foreach ($request->file('media_images') as $image) {
                if ($image->isValid()) {
                    $mediaAssets[] = [
                        'path' => $image->store('posts/images', 'public'),
                        'original_name' => $image->getClientOriginalName(),
                        'size' => $image->getSize(),
                        'mime_type' => $image->getMimeType(),
                    ];
                }
            }
        } elseif ($validated['type'] === 'file' && $request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $file) {
                if ($file->isValid()) {
                    $mediaAssets[] = [
                        'path' => $file->store('posts/files', 'public'),
                        'original_name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                    ];
                }
            }
        }

        if (!empty($mediaAssets)) {
            $postData['media_assets'] = $mediaAssets;
        }

        if ($validated['status'] === 'published') {
            $postData['published_at'] = now();
        }

        $post = Post::create($postData);

        return redirect()->route('posts.index')->with('success', 'Success!');
    }

    public function show(Post $post)
    {
        $post->loadCount(['comments', 'likes']);

        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $creatorPlans = Auth::user()->plans()
            ->where('is_active', true)
            ->orderBy('monthly_amount', 'asc')
            ->get();

        return view('posts.edit', compact('post', 'creatorPlans'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'cover' => 'nullable|image|max:2048',
            'title' => 'required|string|max:255',
            'content_text' => 'nullable|string',
            'media_files' => 'nullable|array',
            'media_files.*' => 'file|max:307200',
            'media_images' => 'nullable|array',
            'media_images.*' => 'image|max:10240',
            'visibility' => 'required|in:all,supporters',
            'supporter_min_amount' => 'required_if:visibility,supporters|integer|min:0',
            'comment_permission' => 'required|in:all,supporters,none',
            'status' => 'required|in:draft,published,archived',
            'remove_media' => 'nullable|array',
            'remove_media.*' => 'integer',
        ]);

        // If visibility is all, reset supporter amount
        if ($validated['visibility'] === 'all') {
            $validated['supporter_min_amount'] = 0;
        } else {
            // Validate that the selected amount matches one of creator's plans
            $creatorPlanAmounts = Auth::user()->plans()
                ->where('is_active', true)
                ->pluck('monthly_amount')
                ->toArray();

            if (!in_array((int)$validated['supporter_min_amount'], $creatorPlanAmounts)) {
                return redirect()->back()
                    ->with('error', 'Invalid support amount selected.')
                    ->withInput();
            }
        }

        // if visibility or comment_permission is set to supporters, ensure user has at least one active plan
        $creatorPlansCount = Auth::user()->plans()->where('is_active', true)->count();
        
        if ($creatorPlansCount === 0) {
            if ($validated['visibility'] === 'supporters') {
                return redirect()->back()
                    ->with('error', 'You need to create at least one support plan to set visibility as "Supporters Only".')
                    ->withInput();
            }
            
            if ($validated['comment_permission'] === 'supporters') {
                return redirect()->back()
                    ->with('error', 'You need to create at least one support plan to set comment permission as "Supporters Only".')
                    ->withInput();
            }
        }

        $updateData = [
            'title' => $validated['title'],
            'content_text' => $validated['content_text'],
            'visibility' => $validated['visibility'],
            'supporter_min_amount' => $validated['supporter_min_amount'] ?? 0,
            'comment_permission' => $validated['comment_permission'],
            'status' => $validated['status'],
        ];

        // handle cover
        if ($request->hasFile('cover')) {
            if ($post->cover) {
                Storage::disk('public')->delete($post->cover);
            }
            $updateData['cover'] = $request->file('cover')->store('posts/covers', 'public');
        } elseif ($request->has('remove_cover')) {
            if ($post->cover) {
                Storage::disk('public')->delete($post->cover);
            }
            $updateData['cover'] = null;
        }

        // handle media
        $currentMedia = $post->media_assets ?: [];
        
        if ($request->has('remove_media')) {
            foreach ($request->remove_media as $index) {
                if (isset($currentMedia[$index])) {
                    Storage::disk('public')->delete($currentMedia[$index]['path']);
                    unset($currentMedia[$index]);
                }
            }
            $currentMedia = array_values($currentMedia);
        }

        if ($post->type === 'image' && $request->hasFile('media_images')) {
            foreach ($request->file('media_images') as $image) {
                if ($image->isValid()) {
                    $currentMedia[] = [
                        'path' => $image->store('posts/images', 'public'),
                        'original_name' => $image->getClientOriginalName(),
                        'size' => $image->getSize(),
                        'mime_type' => $image->getMimeType(),
                    ];
                }
            }
        } elseif ($post->type === 'file' && $request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $file) {
                if ($file->isValid()) {
                    $currentMedia[] = [
                        'path' => $file->store('posts/files', 'public'),
                        'original_name' => $file->getClientOriginalName(),
                        'size' => $file->getSize(),
                        'mime_type' => $file->getMimeType(),
                    ];
                }
            }
        }

        $updateData['media_assets'] = !empty($currentMedia) ? $currentMedia : null;

        if ($validated['status'] === 'published' && !$post->published_at) {
            $updateData['published_at'] = now();
        } elseif ($validated['status'] === 'draft') {
            $updateData['published_at'] = null;
        }

        $post->update($updateData);

        return redirect()->route('posts.show', $post)->with('success', 'Success!');
    }

    public function destroy(Post $post)
    {
        // delete files
        if ($post->cover) {
            Storage::disk('public')->delete($post->cover);
        }

        if ($post->media_assets) {
            foreach ($post->media_assets as $asset) {
                Storage::disk('public')->delete($asset['path']);
            }
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }

    ////=================== Public Methods ==================////

    public function publicIndex(Request $request)
    {
        $type = $request->get('type', 'all');
        $query = Post::published()->with('user');
        
        if ($type !== 'all') {
            //todo:
            $query->byType($type);
        }

        $posts = $query->latest('published_at')->paginate(12)->withQueryString();

        return view('posts.public-index', compact('posts', 'type'));
    }

    public function publicShow(Post $post)
    {
        if (!$post->isVisibleTo(auth()->user())) {
            return redirect()->route('creators.profile', ['creator_id' => $post->creator->creator_id, 'list' => 'plans', '#creator-profile-tablist']);
        }

        //todo:
        $post->load('user', 'comments.user');

        return view('posts.public-show', compact('post'));
    }

    public function storeComment(Request $request, Post $post)
    {
        $validated = $request->validate([
            'comment_text' => 'required|string|max:1000',
        ]);

        Comment::create([
            'user_id' => auth()->id(),
            'post_id' => $post->id,
            'comment_text' => $validated['comment_text'],
        ]);

        return redirect()->back()->with('success', 'Success!');
    }

    public function destroyComment(Comment $comment)
    {
        $comment->delete();
        return redirect()->back()->with('success', 'Success!');
    }

    public function toggleLike(Post $post)
    {
        $user = auth()->user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to like content.');
        }

        // Check if user has permission to view the post
        if (!$post->isVisibleTo($user)) {
            return redirect()->back()->with('error', 'No permission to interact with this content.');
        }

        // Check if user already liked this post
        $existingLike = Like::where('user_id', $user->id)->where('post_id', $post->id)->first();

        if ($existingLike) {
            // User already liked this post, cannot like again
            return redirect()->back()->with('info', 'You have already liked this content.');
        }

        // Create new like
        Like::create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
        
        // Increment likes count
        $post->increment('likes_count');

        //Increment Coins
        $post->creator->increment('coin_balance');

        return redirect()->back()->with('success', 'Liked successfully!');
    }
}