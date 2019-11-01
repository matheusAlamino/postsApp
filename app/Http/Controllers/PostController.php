<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\PostRequest;
use App\Post;
use Illuminate\Http\Request;


class PostController extends Controller
{
    private $userId;

    public function __construct()
    {
        $this->userId = auth()->user()->id ?? 0;
    }

    public function index()
    {
        $posts = Post::with('user:id,name')->latest()->paginate(6);

        if ($posts)
            return response()->json($posts, 200);

        return response()->json(['message' => 'Não existem posts'], 400);
    }

    public function show($id)
    {
        $post = Post::with('comments')->find($id);
        $totalComments = 0;

        if ($post) {
            $totalComments = $post->comments->count();
            return response()->json([
                'data' => $post,
                'totalComments' => $totalComments
            ]);
        }

        return response()->json(['message' => 'Post não existe'], 400);
    }

    public function store(PostRequest $request)
    {
        try {
            $post = Post::create(
                [
                    'text' => $request->text,
                    'id_usuario' => $this->userId
                ]
            );

            return response()->json($post, 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Ocorreu um erro processando a solicitação'], 500);;
        }
    }

    public function update($id, PostRequest $request)
    {
        $post = Post::find($id);

        $post->update($request->all());

        $post->save();

        return response()->json(['message' => 'saved!']);
    }

    public function delete($id)
    {
        $post = Post::find($id);

        $this->authorize('edit', $post);

        $post->destroy($id);

        return response()->json(['message' => 'Deleted!']);
    }

    public function storeComment($id, CommentRequest $request)
    {
        try {
            $comment = Comment::create(
                [
                    'description' => $request->description,
                    'id_post' => $id,
                    'id_usuario' => $this->userId
                ]
            );

            return response()->json($comment, 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Ocorreu um erro processando a solicitação'], 500);;
        }
    }
}
