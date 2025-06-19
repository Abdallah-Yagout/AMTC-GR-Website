<?php

namespace App\Http\Controllers;

use App\Models\Forum;
use Illuminate\Http\Request;

class ForumController extends Controller
{
    public function index()
    {
        $data['forums']=Forum::all();
        $data['popularForums'] = Forum::withCount('upvotes')
            ->with('user')
            ->orderBy('upvotes_count', 'desc')
            ->take(10)
            ->get();

        $data['newestForums'] = Forum::withCount('upvotes')
            ->with('user')
            ->latest()
            ->take(10)
            ->get();
        $data['recentDiscussions'] = Forum::latest()->take(3)->get();
        $data['popularPost'] = Forum::withCount('upvotes')->orderBy('upvotes_count', 'desc')->first();

        return view('community.index',$data);
    }
    public function toggleUpvote(Forum $forum)
    {
        $user = auth()->user();

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

}
