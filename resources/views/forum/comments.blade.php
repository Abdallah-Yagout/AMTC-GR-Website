@foreach($comments as $comment)
    @include('forum.comment', [
        'comment' => $comment,
        'forum' => $forum
    ])
@endforeach
