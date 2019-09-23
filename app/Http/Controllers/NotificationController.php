<?php

namespace App\Http\Controllers;

use App\Http\Requests\NotificationRequest;
use App\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private $userId;

    public function __construct()
    {
        $this->userId = auth()->user()->id ?? 0;
    }

    public function index()
    {
        $notifications = Notification::where('id_usuario', $this->userId);
        if ($notifications->exists()) {
            foreach ($notifications->paginate(6) as $notification) {
                $this->authorize('read', $notification);
            }
            return response()->json($notifications->paginate(6), 200);
        }
    }

    public function show($id)
    {
        $notification = Notification::find($id);

        $this->authorize('read', $notification);

        return response()->json($notification);
    }

    public function delete($id)
    {
        $notification = Notification::find($id);

        $can = $this->authorize('edit', $notification);

        if ($can) {
            $notification->destroy($id);
            return response()->json(['message' => 'Deleted!']);
        }
    }
}
