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
        $countNotSeen = 0;
        if ($notifications->exists()) {
            foreach ($notifications->latest()->paginate(6) as $notification) {
                $this->authorize('read', $notification);

                if ($notification->seen == 0)
                    $countNotSeen += 1;
            }

            return response()->json([
                'data' => $notifications->latest()->paginate(6),
                'countNotSeen' => $countNotSeen
            ], 200);
        }
    }

    public function show($id)
    {
        $notification = Notification::find($id);

        $this->authorize('read', $notification);

        return response()->json($notification);
    }

    public function update($id)
    {
        $notification = Notification::find($id);

        $notification->update([
            'seen' => true
        ]);

        $notification->save();

        return response()->json(['message' => 'saved!']);
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
