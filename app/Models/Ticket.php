<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

class Ticket extends Model
{
    use CentralConnection;

    protected $table = 'tickets';

    protected $fillable = [
        'user_id',
        'tenant_id',
        'assigned_to',
        'department',
        'priority',
        'subject',
        'labels_id',
        'category_id',
        'is_archived',
        'is_locked',
        'status',
    ];

    /**
     * Get Tenant RelationShip
     */
    public function tenant()
    {
        return $this->hasOne(Tenant::class, 'id', 'tenant_id');
    }

    /**
     * Get Assigned User RelationShip
     */
    public function assigned_user()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get Messages RelationShip
     */
    public function messages()
    {
        return $this->hasMany(Message::class, 'ticket_id');
    }

    /**
     * Get Categories RelationShip
     */
    public function categories()
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    /**
     * Get Labels RelationShip
     */
    public function labels()
    {
        return $this->belongsToMany(TicketLabel::class, 'label_ticket', 'ticket_id', 'label_id');
    }

    public function is_closed()
    {
        return $this->status == 'closed';
    }

    public function is_archived()
    {
        return $this->is_archived;
    }

    public function is_locked()
    {
        return $this->is_locked;
    }

    public static function get_tenant_user($tenant_db, $user_id)
    {
        $template = config('database.default');
        $template_connection = config("database.connections.{$template}");
        $template_connection['database'] = $tenant_db;

        config()->set('database.connections.dynamic_tenant', $template_connection);

        return DB::connection('dynamic_tenant')->table('users')->where('id', $user_id)->first();
    }
}
