<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\NotificationMarkedAsRead;
use App\Repositories\UserRepo;

class NotificationController extends Controller
{
    protected $user;

    public function __construct(UserRepo $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('pages.notifications.index');
    }

    /**
     * Enable/Disable user notifications.
     */
    public function update_is_notifiable(Request $request)
    {
        $keys = ['is_notifiable'];
        $this->user->update(auth()->id(), $request->only($keys));

        return Qs::jsonUpdateOk();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->user->deleteNotification($id);

        return back()->with('flash_success', __('msg.del_ok'));
    }

    /**
     * Mark the notification as read.
     */
    public function mark_as_read(string $id)
    {
        $notification = auth()->user()->unreadNotifications()->where('id', $id)->first();

        if (is_null($notification))
            return back()->with('flash_error', 'Notification not found.');

        $notification->markAsRead();
        auth()->user()->notify(new NotificationMarkedAsRead($id));

        return back()->with('flash_success', __('msg.update_ok'));
    }

    /**
     * Mark all user's notifications as read.
     */
    public function mark_all_read(Request $request)
    {
        $request->user()->unreadNotifications()->get()->each(function ($n) {
            $n->markAsRead();
        });

        return back()->with('flash_success', __('msg.update_ok'));
    }

    /**
     * Update the user's web push subscription/device token.
     */
    public function update_firebase_device_token(Request $request)
    {
        if ($request->method() === "DELETE") {
            $token[] = $request->token;
            $this->delete_device_token($token);
            return Qs::jsonUpdateOk();
        }

        // Get token or tokens (in case the user uses multiple devices) from the DB 
        $current_tokens = is_null(auth()->user()->firebase_device_token) ? [] : unserialize(auth()->user()->firebase_device_token);
        $incoming_token = $request->token;

        if (is_null($current_tokens) or !in_array($incoming_token, $current_tokens)) {
            $current_tokens[] = $incoming_token; // Add a new one

            $data = ['firebase_device_token' => serialize($current_tokens)];
            $this->user->update(auth()->id(), $data);
        }

        return Qs::jsonUpdateOk();
    }

    /**
     * Delete the user's web push subscription/device token.
     */
    public function delete_device_token(array $token)
    {
        $current_tokens = unserialize(auth()->user()->firebase_device_token);
        $new_tokens = array_diff($current_tokens, $token);

        $data = ['firebase_device_token' => serialize($new_tokens)];

        return $this->user->update(auth()->id(), $data);
    }
}