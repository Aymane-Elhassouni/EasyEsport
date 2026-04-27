<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())->latest()->get();
        Notification::where('user_id', Auth::id())->update(['is_read' => true]);
        return view('pages.notifications', compact('notifications'));
    }

    public function dropdown()
    {
        $notifications = Notification::where('user_id', Auth::id())->latest()->take(5)->get();
        $unread = Notification::where('user_id', Auth::id())->where('is_read', false)->count();

        return response()->json([
            'unread' => $unread,
            'notifications' => $notifications->map(fn($n) => [
                'id'         => $n->id,
                'title'      => $n->title,
                'message'    => $n->message,
                'icon'       => $n->icon,
                'action_url' => $n->action_url,
                'is_read'    => $n->is_read,
                'time'       => $n->created_at->diffForHumans(),
            ]),
        ]);
    }

    public function destroy(Notification $notification)
    {
        abort_if($notification->user_id !== Auth::id(), 403);
        $notification->delete();
        return response()->json(['ok' => true]);
    }

    public function clear()
    {
        Notification::where('user_id', Auth::id())->delete();
        return back()->with('success', 'All notifications cleared.');
    }
}
