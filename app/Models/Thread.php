<?php

namespace App\Models;

use App\Models\Message;
use Cmgmyr\Messenger\Models\Models;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread as OriginalThread;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Thread extends OriginalThread
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    /**
     * Messages relationship with trashed.
     *
     * @codeCoverageIgnore
     */
    public function messagesWithTrashed()
    {
        return $this->hasMany(Models::classname(Message::class), 'thread_id', 'id')->withTrashed();
    }

    /**
     * Returns all the soft delted threads.
     */
    public static function getTrashed()
    {
        return Thread::onlyTrashed()->get();
    }

    /**
     * Restore a thread by id.
     */
    public static function restoreThread($id)
    {
        return Thread::where('id', $id)->restore();
    }

    /**
     * Delete a soft deleted thread by id.
     */
    public static function force_delete($id)
    {
        return Thread::where('id', $id)->forceDelete();
    }

    /**
     * Remove participants from thread.
     *
     * @param mixed $userId
     *
     * @return void
     */
    public function removeParticipant2($userId, $threadId)
    {
        Models::participant()->where('thread_id', $threadId)->where('user_id', $userId)->delete();
    }

    /**
     * Generates array of participant information.
     *
     * @param mixed $userId
     * @param array $columns
     *
     * @return string
     */
    public function participantsArray($userId = null, $columns = ['name', 'id'])
    {
        $participantsTable = Models::table('participants');
        $usersTable = Models::table('users');
        $userPrimaryKey = Models::user()->getKeyName();
        $tablePrefix = $this->getConnection()->getTablePrefix();

        $columnString = implode(", ' ', $tablePrefix$usersTable.", $columns);
        $selectString = "$tablePrefix$usersTable.$columnString";

        $participantNames = $this->getConnection()->table($usersTable)
            ->join($participantsTable, "$usersTable.$userPrimaryKey", '=', "$participantsTable.user_id")
            ->where("$participantsTable.thread_id", $this->id)
            ->where("$participantsTable.deleted_at", '=', NULL)
            ->select($this->getConnection()->raw($selectString));

        if ($userId !== null) {
            $participantNames->where("$usersTable.$userPrimaryKey", '!=', $userId);
        }

        return $participantNames->get();
    }

    /**
     * See is the thread has any soft deleted participant.
     *
     * @param int $threadId
     *
     * @return bool
     */
    public function hasDirtyParticipant($threadId)
    {
        $trashed = Participant::onlyTrashed()->where('thread_id', $threadId)->get();

        return count($trashed) > 0 ? true : false;
    }

    /**
     * Checks to see if a user is a current participant of the thread.
     *
     * @param mixed $userId
     *
     * @return bool
     */
    public function isDirtyParticipant($userId)
    {
        $participants = $this->participants()->onlyTrashed()->where('user_id', '=', $userId);

        return $participants->count() > 0;
    }
}
