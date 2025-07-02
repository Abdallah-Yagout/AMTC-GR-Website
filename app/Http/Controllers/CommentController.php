<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Forum;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Forum $forum)
    {
        $request->validate([
            'body' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = $forum->comments()->create([
            'body' => $request->body,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id
        ]);

        // Load relationships for the view
        $comment->load('user', 'replies');

        return response()->json([
            'success' => true,
            'html' => $request->parent_id
                ? view('forum.reply', ['reply' => $comment])->render()
                : view('forum.comment', ['comment' => $comment])->render(),
            'comment_count' => $forum->comments()->count()
        ]);
    }
    public function loadMoreComments(Forum $forum, Request $request)
    {
        $page = $request->input('page', 2); // Default to page 2 since page 1 is loaded initially
        $perPage = $request->input('per_page', 10);


        $comments = $forum->comments()
            ->with(['user', 'replies'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
        return response()->json([
            'html' => view('forum.comments', [
                'comments' => $comments,
                'forum' => $forum
            ])->render(),
            'has_more' => $comments->hasMorePages()
        ]);
    }
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json(['success' => true]);
    }
}
