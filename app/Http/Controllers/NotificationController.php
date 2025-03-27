<?php

namespace App\Http\Controllers;

use App\Helpers\Qs;
use App\Http\Controllers\Controller;
use App\Notifications\NotificationMarkedAsRead;
use App\Repositories\UserRepo;
use Illuminate\Http\Request;
use NotificationChannels\Fcm\FcmChannel;

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
        $channels = Qs::getActiveNotificationChannels(auth()->user(), false, true, true, true);

        $data['notification_status'] = Qs::is_serialized(auth()->user()->is_notifiable) ? unserialize(auth()->user()->is_notifiable)['status'] : 0;
        $data['email_channel_on'] = in_array('mail', $channels);
        $data['sms_channel_on'] = in_array('vonage', $channels);
        $data['push_channel_on'] = in_array(FcmChannel::class, $channels);

        return view('pages.notifications.index', $data);
    }

    /**
     * Enable/Disable user notifications.
     */
    public function update_is_notifiable(Request $request)
    {
        $value['status'] = $request->status;
        $value['on_email_channel'] = $request->on_email_channel;
        $value['on_sms_channel'] = $request->on_sms_channel;
        $value['on_push_channel'] = $request->on_push_channel;
        $data = ['is_notifiable' => serialize($value)];

        $this->user->update(auth()->id(), $data);

        return back()->with('flash_success', __('msg.update_ok'));
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
    public function mark_as_read(string $id, $flash = true)
    {
        $notification = auth()->user()->unreadNotifications()->where('id', $id)->first();

        if ($notification === null)
            return back()->with('flash_error', 'Notification not found.');

        $notification->markAsRead();
        auth()->user()->notify(new NotificationMarkedAsRead($id));

        return $flash ? back()->with('flash_success', __('msg.update_ok')) : true;
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
        $current_tokens = auth()->user()->firebase_device_token === null ? [] : unserialize(auth()->user()->firebase_device_token);
        $incoming_token = $request->token;

        if ($current_tokens === null or !in_array($incoming_token, $current_tokens)) {
            $current_tokens[] = $incoming_token; // Add a new one or the only one

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