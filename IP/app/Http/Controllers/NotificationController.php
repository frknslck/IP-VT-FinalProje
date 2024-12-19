<?php

namespace App\Http\Controllers;
use App\Models\Notification;
use App\Models\ActionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->orderBy('pivot_created_at', 'desc')->get(); 
    
        return view('notifications.index', compact('notifications'));
    }

    public function show(Notification $notification)
    {
        if (auth()->user()->notifications->contains($notification)) {
            auth()->user()->notifications()->updateExistingPivot($notification->id, ['is_read' => true]);

            return view('notifications.show', compact('notification'));
        } else {
            return redirect()->route('notifications.index')->with('error', 'You cannot view other user notifications.');
        }
    }

    public function markAsRead(Notification $notification)
    {
        auth()->user()->notifications()
            ->updateExistingPivot($notification->id, ['is_read' => true]);

        return back()->with('success', 'Notification marked as read.');
    }

    public function send(Request $request)
    {
        $notification = Notification::create($request->only('from', 'title', 'message'));

        $notification->users()->attach($request->user_ids);

        ActionLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'target' => 'notification',
            'status' => 'success',
            'ip_address' => request()->ip(),
            'details' => 'Notification sent. ID: ' . $notification->id,
        ]);

        return back()->with('success', 'Notification sent successfully.');
    }
}
