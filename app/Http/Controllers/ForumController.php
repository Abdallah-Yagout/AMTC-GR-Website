<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ForumController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $popularForums = Forum::withCount('upvotes')
            ->with('user')
            ->take(10)
            ->get()
            ->map(function ($forum) use ($user) {
                $forum->upvotedByMe = $user ? $forum->upvotes()->where('user_id', $user->id)->exists() : false;
                return $forum;
            });

        $newestForums = Forum::withCount('upvotes')
            ->with('user')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($forum) use ($user) {
                $forum->upvotedByMe = $user ? $forum->upvotes()->where('user_id', $user->id)->exists() : false;
                return $forum;
            });

        return view('forum.index', [
            'forums' => Forum::all(),
            'popularForums' => $popularForums,
            'newestForums' => $newestForums,
            'recentDiscussions' => Forum::latest()->take(3)->get(),
            'popularPost' => Forum::withCount('upvotes')->orderBy('upvotes_count', 'desc')->first(),
        ]);
    }
    public function toggleUpvote(Forum $forum)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'error' => 'You must be logged in to upvote'
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

    public function view(Forum $forum)
    {
        return view('forum.view', [
            'forum' => $forum,
            'newDiscussions' => Forum::latest()->take(5)->get(),
            'popularPosts' => Forum::orderBy('upvotes', 'desc')->take(5)->get()
        ]);
    }
}
