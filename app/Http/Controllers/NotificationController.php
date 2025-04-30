<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification; // Use this specific class
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Make sure User model is imported

class NotificationController extends Controller
{
    /**
     * Mark the specified notification as read.
     *
     * @param  \Illuminate\Notifications\DatabaseNotification  $notification
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(DatabaseNotification $notification) // Route model binding works!
    {
        // Ensure the notification belongs to the authenticated user
        // Check both ID and type for robustness
        if ($notification->notifiable_id === Auth::id() && $notification->notifiable_type === User::class) { // Use User::class
            // Only mark as read if it's currently unread
            if ($notification->unread()) {
                $notification->markAsRead();
            }
            // Always return the current count, even if it was already read
            return response()->json([
                'success' => true,
                'unread_count' => Auth::user()->unreadNotifications()->count() // Use the efficient query
            ]);
        }

        // If notification doesn't belong to the user or something went wrong
        return response()->json(['success' => false, 'message' => 'Unauthorized or Notification not found.'], 403);
    }

    /**
     * Mark all unread notifications as read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllRead(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();

        return response()->json([
            'success' => true,
            'unread_count' => 0 // All marked as read
        ]);
    }
}