<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class TicketMessage extends Model
{
    use CentralConnection;

    protected $table = 'ticket_messages';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * Get Ticket RelationShip
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    /**
     * Get the central user from the central connection
     */
    public function central_user($user_id)
    {
        return $this->join('users', $this->getTable() . '.user_id', '=', 'users.id')->where($this->getTable() . '.user_id', $user_id)->first();
    }
}
