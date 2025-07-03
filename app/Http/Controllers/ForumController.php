<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $activeTab = request()->get('tab', 'newest'); // Get active tab from request

        $popularForums = Forum::select('forums.*')
            ->withCount(['upvotes as upvotes_count'])
            ->groupBy('forums.id')
            ->having('upvotes_count', '>', 0)
            ->with('user')
            ->orderBy('upvotes_count', 'desc')
            ->paginate(10)
            ->withQueryString() // Preserve all query parameters including 'tab'
            ->through(function ($forum) use ($user) {
                $forum->upvotedByMe = $user ? $forum->upvotes()->where('user_id', $user->id)->exists() : false;
                return $forum;
            });

        $newestForums = Forum::withCount('upvotes')
            ->with('user')
            ->latest()
            ->paginate(10)
            ->withQueryString() // Preserve all query parameters including 'tab'
            ->through(function ($forum) use ($user) {
                $forum->upvotedByMe = $user ? $forum->upvotes()->where('user_id', $user->id)->exists() : false;
                return $forum;
            });

        return view('forum.index', [
            'forums' => Forum::all(),
            'popularForums' => $popularForums,
            'newestForums' => $newestForums,
            'recentDiscussions' => Forum::latest()->take(3)->get(),
            'popularPost' => Forum::withCount('upvotes')->orderBy('upvotes_count', 'desc')->first(),
            'activeTab' => $activeTab, // Pass active tab to view

        ]);
    }
    public function toggleUpvote(Forum $forum)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'error' => __('You must be logged in to upvote')
            ], 401);
        }

        // Check if user already upvoted
        $existingVote = $forum->upvotes()->where('user_id', $user->id)->first();

        if ($existingVote) {
            $existingVote->delete();
            $action = 'removed';
        } else {

            $forum->upvotes()->create([
                'user_id' => $user->id,
            ]);
            $action = 'upvoted';
        }

        return response()->json([
            'upvotes' => $forum->upvotes()->count(),
            'action' => $action,
        ]);
    }

    public function show($slug)
    {
        $user = auth()->user();

        // Get the requested forum with upvote check
        $forum = Forum::withCount(['comments', 'upvotes'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Add upvotedByMe status for the main forum
        $forum->upvotedByMe = $user ? $forum->upvotes()->where('user_id', $user->id)->exists() : false;

        // Get popular posts with upvote check
        $popularPosts = Forum::select('forums.*')
            ->withCount(['upvotes as upvotes_count'])
            ->groupBy('forums.id')
            ->having('upvotes_count', '>', 0)
            ->with('user')
            ->orderBy('upvotes_count', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($forum) use ($user) {
                $forum->upvotedByMe = $user ? $forum->upvotes()->where('user_id', $user->id)->exists() : false;
                return $forum;
            });

        // Get newest discussions with upvote check
        $newDiscussions = Forum::withCount(['comments', 'upvotes'])
            ->latest()
            ->take(3)
            ->get()
            ->map(function ($forum) use ($user) {
                $forum->upvotedByMe = $user ? $forum->upvotes()->where('user_id', $user->id)->exists() : false;
                return $forum;
            });

        return view('forum.view', [
            'forum' => $forum,
            'newDiscussions' => $newDiscussions,
            'popularPosts' => $popularPosts
        ]);
    }
    public function edit(Forum $forum)
    {

        return response()->json([
            'title' => $forum->title,
            'body' => $forum->body,
            'image_url' => $forum->image ? Storage::url($forum->image) : null
        ]);
    }
    public function update(Request $request, Forum $forum)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'remove_image' => 'nullable|boolean'
        ]);

        // Handle image upload/removal
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($forum->image) {
                Storage::delete($forum->image);
            }

            // Store new image
            $path = $request->file('image')->store('posts', 'public');
            $validated['image'] = $path;
        } elseif ($request->input('remove_image')) {
            // Remove existing image if requested
            if ($forum->image) {
                Storage::delete($forum->image);
            }
            $validated['image'] = null;
        }

        // Update slug if title changed
        if ($forum->title !== $request->title) {
            $validated['slug'] = Str::slug($request->title);
        }

        $forum->update($validated);

        return back();
    }
    public function destroy(Forum $forum)
    {

        // Delete associated image if exists
        if ($forum->image) {
            Storage::delete($forum->image);
        }

        $forum->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully'
        ]);
    }

    public function store(Request $request)
    {
        // 1. Validate the input
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|max:2048', // 2MB max
        ]);

        // 2. Generate a unique slug
        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 1;
        while (Forum::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        // 3. Handle image upload if present
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        // 4. Create the post
        $post = Forum::create([
            'title' => $validated['title'],
            'slug' => $slug,
            'body' => $validated['body'],
            'image' => $imagePath,
            'user_id' => auth()->id(), // optional

        ]);

        // 5. Redirect or respond
        return redirect()->route('forum.index')->with('success', 'Post created successfully.');
    }

}


