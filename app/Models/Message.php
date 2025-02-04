<?php

namespace App\Models;

use Cmgmyr\Messenger\Models\Message as OriginalMessage;
use Cmgmyr\Messenger\Models\Models;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Message extends OriginalMessage
{
    use LogsActivity;

    /**
     * Delete a message.
     *
     * @param int $messageId
     *
     * @return mixed
     */
    public function deleteMessage($messageId)
    {
        return OriginalMessage::where('id', $messageId)->delete();
    }

    public function deleteMessageForever($messageId)
    {
        return DB::table($this->table)->where("id", $messageId)->delete();
    }

    public function restoreDeletedMessage($messageId)
    {
        return OriginalMessage::where('id', $messageId)->restore();
    }

    public function updateMessage($messageId, $data)
    {
        return OriginalMessage::where('id', $messageId)->update($data);
    }

    public function deletor()
    {
        return $this->belongsTo(Models::user(), 'deleted_by', 'id');
    }

    public function user()
    {
        return $this->belongsTo(Models::user());
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->dontSubmitEmptyLogs()
            ->logOnlyDirty();
    }
}
