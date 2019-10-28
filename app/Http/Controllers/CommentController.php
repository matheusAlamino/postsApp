<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\CommentRequest;
use App\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    private $userId;

    public function __construct()
    {
        $this->userId = auth()->user()->id ?? 0;
    }

    public function show($id)
    {
        $comment = Comment::find($id);

        if ($comment)
            return response()->json($comment);

        return response()->json(['message' => 'Comentário não existe'], 400);
    }

    public function update($id, CommentRequest $request)
    {
        $comment = Comment::find($id);

        $comment->update($request->all());

        $comment->save();

        return response()->json(['message' => 'saved!']);
    }

    public function delete($id)
    {
        $comment = Comment::find($id);
        $post = Post::find($comment->id_post);

        $this->authorize('edit', [$comment, $post]);

        $comment->destroy($id);

        return response()->json(['message' => 'Deleted!']);
    }
}
