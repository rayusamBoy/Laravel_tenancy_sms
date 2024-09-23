<?php

namespace App\Http\Controllers\SupportTeam;

use App\User;
use App\Helpers\Qs;
use Carbon\Carbon;
use App\Models\Thread;
use App\Models\Message;
use App\Events\NewMessage;
use App\Events\MessageDeleted;
use App\Http\Controllers\Controller;
use App\Models\StudentRecord;
use App\Notifications\MessageSent;
use App\Models\UserType;
use Cmgmyr\Messenger\Models\Participant;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Throwable;

class MessageController extends Controller implements HasMiddleware
{
    protected $extra_messages_to_count;
    function __construct()
    {
        $this->extra_messages_to_count = 5;
    }

    /**
     * Get the middleware that should be assigned to the controller.
     */
    public static function middleware(): array
    {
        return [
            new Middleware('superAdmin', only: ['store', 'destroy', 'remove_participant', 'create']),
        ];
    }

    /**
     * Show all of the message threads to the user.
     *
     * @return mixed
     */
    public function index()
    {
        // All threads, ignore deleted/archived participants
        $threads = Thread::getAllLatest()->get();

        // All threads that user is participating in
        // $threads = Thread::forUser(Auth::id())->latest('updated_at')->get();

        // All threads that user is participating in, with new messages
        // $threads = Thread::forUserWithNewMessages(Auth::id())->latest('updated_at')->get();

        $users = self::get_users();
        $user_types = UserType::all();
        $settings = Qs::getSettings();

        return view('messenger.index', compact('threads', 'users', 'user_types', 'settings'));
    }

    /**
     * Shows a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $id = Qs::decodeHash($id);

        try {
            $thread = Thread::withTrashed()->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->route('messages.index')->with('flash_error', 'The thread was not found.');
        }

        // show current user in list if not a current participant
        // $users = User::whereNotIn('id', $thread->participantsUserIds())->get();

        // don't show the current user in list
        $userId = Auth::id();
        $users = User::whereNotIn('id', $thread->participantsUserIds($userId))->get();
        $extra_messages_to_count = $this->extra_messages_to_count;
        $unread_messages_count = $thread->userUnreadMessagesCount(auth()->id());

        $user_as_participant = Participant::where(['thread_id' => $thread->id, 'user_id' => Auth::id()])->get();

        $thread->markAsRead($userId);
        $settings = Qs::getSettings();

        return view('messenger.show', compact('thread', 'users', 'user_as_participant', 'extra_messages_to_count', 'unread_messages_count', 'settings'));
    }

    /**
     * Get all users except the authenticated one.
     *
     * @return mixed
     */
    public function get_users()
    {
        $users = User::where('id', '!=', Auth::id())->get();

        return $users;
    }

    /**
     * Creates a new message thread.
     *
     * @return mixed
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();

        return view('messenger.create', compact('users'));
    }

    /**
     * Stores a new message thread.
     *
     * @return mixed
     */
    public function store()
    {
        $input = Request::all();

        Validator::make($input, [
            'subject' => 'required|string|min:3|max:300',
            'message' => 'required|string|min:2|max:500',
            'recipients' => 'required_without:user_types|array',
            'user_types' => 'required_without:recipients|array'
        ])->validate();

        $thread = Thread::create([
            'subject' => $input['subject'],
        ]);

        // Message
        Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $input['message'],
        ]);

        // Sender
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'last_read' => new Carbon(),
        ]);

        // Recipients - individuals
        if (Request::has('recipients')) {
            $thread->addParticipant($input['recipients']);
        }

        // Recipients - by user type(s)
        if (Request::has('user_types')) {
            foreach ($input['user_types'] as $type) {
                // If active students
                if ($type == 0)
                    $ids = StudentRecord::where('grad', 0)->with('user')->get()->where('user.user_type', 'student')->pluck('user_id')->toArray();
                elseif ($type == 1)
                    $ids = StudentRecord::where('grad', 1)->with('user')->get()->where('user.user_type', 'student')->pluck('user_id')->toArray();
                else
                    $ids = User::whereUserType($type)->get()->pluck('id')->toArray();

                $thread->addParticipant($ids);
            }
        }

        return redirect()->route('messages.index')->with('flash_success', __('msg.msg_thread_created'));
    }

    /**
     * Adds a new message to a current thread.
     *
     * @param $id
     * @return mixed
     */
    public function update($id)
    {
        $input = Request::all();

        Validator::make($input, [
            'message' => 'required|string|min:2|max:500',
            'recipients' => 'sometimes|array'
        ])->validate();

        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return Qs::json('The thread was not found.', false);
        }

        try {
            // Message
            $message = Message::create([
                'thread_id' => $thread->id,
                'user_id' => Auth::id(),
                'body' => Request::input('message'),
            ]);

            // Add replier as a participant
            $participant = Participant::firstOrCreate([
                'thread_id' => $thread->id,
                'user_id' => Auth::id(),
            ]);

            $participant->last_read = new Carbon();
            $participant->save();

            // Recipients
            if (Request::has('recipients'))
                $thread->addParticipant($input['recipients']);
        } catch (Throwable $e) {
            // Rollback the created message.
            $message->deleteMessageForever($message->id);
            return Qs::json(null, false, ['msg' => "Something went wrong. Cannot create a message.", 'ok' => false, 'complete' => true]);
        }

        try {
            $message = Message::with(['user', 'deletor'])->find($message->id);
            $users = Participant::where('thread_id', $thread->id)->with('user')->get()->where('user.id', '<>', auth()->id())->whereNotNull('user')->pluck('user');
            // Update user photo path to full asset url
            $message['user']['photo'] = tenant_asset($message['user']['photo']);

            Notification::sendNow($users, new MessageSent($message));
            broadcast(new NewMessage($message));

            return Qs::jsonOnyWithMsg('scrollToBtn');
        } catch (Throwable $e) {
            $message->deleteMessageForever($message->id);
            return Qs::json(null, false, ['msg' => "Something went wrong. Cannot send a message.", 'ok' => false, 'complete' => true]);
        }
    }

    /**
     * Update participant last read.
     *
     * @param int $thread_id
     * @return null
     */
    public function update_participant_last_read($thread_id)
    {
        Participant::where(['thread_id' => $thread_id, 'user_id' => Auth::id()])->update(['last_read' => new Carbon()]);
        return Qs::jsonUpdateOk();
    }

    /**
     * Delete a message thread.
     *
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return back()->with('flash_error', 'The thread was not found.');
        }

        if (!Qs::userIsTeamSA())
            return back()->with('pop_warning', __('msg.denied'));

        $thread->destroy($id);
        return redirect()->route('messages.index')->with('flash_success', __('msg.del_ok'));
    }

    /**
     * Remove Participant.
     *
     * @param int $thread_id
     * @param int $participant_id
     * @return mixed
     */
    public function remove_participant($thread_id, $participant_id)
    {
        $thread_id = Qs::decodeHash($thread_id);
        $participant_id = Qs::decodeHash($participant_id);

        try {
            $thread = Thread::findOrFail($thread_id);
        } catch (ModelNotFoundException $e) {
            if (Thread::onlyTrashed()->find($thread_id)->exists())
                return redirect()->back()->with('flash_info', 'You must restore the thread first to remove the participant.');
            return redirect()->back()->with('flash_error', 'The thread was not found.');
        }

        $thread->removeParticipant2($participant_id, $thread_id);
        return redirect()->back()->with('flash_success', 'Participant has been removed.');
    }

    /**
     * Delete a user creted message.
     *
     * @param int $thread_id
     * @param int $participant_id
     * @return mixed
     */
    public function user_delete($msg_id)
    {
        try {
            $message = Message::findOrFail($msg_id);
        } catch (ModelNotFoundException $e) {
            return Qs::json('The message was not found.', FALSE);
        }

        try {
            $message->updateMessage($msg_id, ['deleted_by' => Auth::id()]);
            $message->deleteMessage($msg_id);
            $message = Message::onlyTrashed()->with(['user', 'deletor'])->find($message->id);
            // Update user photo path to full asset url
            $message['user']['photo'] = tenant_asset($message['user']['photo']);

            broadcast(new MessageDeleted($message));
            return Qs::jsonOnyWithMsg('complete');
        } catch (Throwable $e) {
            // Roll back message actions.
            $message->updateMessage($msg_id, ['deleted_by' => NULL]);
            $message->restoreDeletedMessage($msg_id);

            return Qs::json(null, TRUE, ['ok' => FALSE, 'msg' => "Sorry, something went wrong. Can't delete a message.", 'complete' => true]);
        }
    }

    /**
     * Delete a user creted message.
     *
     * @param int $thread_id
     * @param int $participant_id
     * @return mixed
     */
    public function activate_all_participants($thread_id)
    {
        $thread_id = Qs::decodeHash($thread_id);

        try {
            $thread = Thread::findOrFail($thread_id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with('flash_error', 'The thread was not found.');
        }

        $thread->activateAllParticipants();
        return redirect()->back()->with('flash_success', 'Participants have been activated succussfully.');
    }

    /**
     * Fetch previous messages.
     *
     * @param int $thread_id
     * @param int $current_first_msg_id_in_view
     * @return mixed
     */
    public function fetch_previous($thread_id, $current_first_msg_id_in_view)
    {
        $messages_string = '';
        $messages = Message::withTrashed()->with(['user', 'deletor'])->where('thread_id', $thread_id)->where('id', '<', $current_first_msg_id_in_view)->get()->take(-$this->extra_messages_to_count);

        foreach ($messages as $message) {
            $data['message'] = $message;
            $messages_string .= view('messenger.partials.messages', $data)->render();
        }

        return Qs::json($messages_string);
    }
}
