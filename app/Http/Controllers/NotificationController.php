<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $unreadCount = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();
        
        return view('notification.index', compact('notifications', 'unreadCount'));
    }
    
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();
        
        if ($notification) {
            $notification->is_read = true;
            $notification->save();
        }
        
        return redirect()->back();
    }
    
    public function markAllRead()
    {
        Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        return redirect()->back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
    
    public function unreadCount()
    {
        $count = Notification::where('user_id', Auth::id())
            ->where('is_read', false)
            ->count();
        
        return response()->json(['count' => $count]);
    }
    public function latest()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return response()->json($notifications);
    }
}