<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Notification;
use App\Post;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $userId;

    public function __construct()
    {
        $this->userId = auth()->user()->id ?? 0;
    }

    public function index()
    {
        $user = User::all();

        if ($user)
            return response()->json($user);

        return response()->json(['message' => 'Nao existem usuarios cadastrados'], 400);
    }

    public function show($id)
    {

        $user = User::find($id);

        if ($user)
            return response()->json($user);

        return response()->json(['message' => 'usuario nao existe'], 400);
    }

    public function store(UserRequest $request)
    {
        try {
            $user = User::create($request->all());

            return response()->json(['user_id' => $user->id]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Ocorreu um erro processando a solicitação'], 500);;
        }
    }

    public function update($id, UserRequest $request)
    {

        $user = User::find($id);

        if ($user && $user->id === $this->userId) {
            $user->update($request->all());

            $user->save();

            return response()->json(['message' => 'saved!']);
        }

        return response()->json(['message' => 'Operação não autorizada'], 400);
    }

    public function delete($id)
    {
        $user = User::find($id);

        if ($user && $user->id === $this->userId) {
            User::destroy($id);
            return response()->json(['message' => 'Deleted!']);
        }

        return response()->json(['message' => 'Operação não autorizada'], 400);
    }

    public function updateNotifications($id)
    {
        try {
            $notifications = Notification::where('id_usuario', $id)->where('seen', '0');
            if ($notifications->exists()) {
                foreach ($notifications->get() as $notification) {
                    $notification->update([
                        'seen' => true
                    ]);
                }

                $notification->save();

                return response()->json(['message' => 'saved!']);
            }
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Ocorreu um erro processando a solicitação'], 500);;
        }
    }

    public function myPosts($id)
    {
        $posts = Post::with('user:id,name')->where('id_usuario', $id)->latest()->paginate(3);

        if ($posts)
            return response()->json($posts, 200);

        return response()->json(['message' => 'Você não possui posts'], 400);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
