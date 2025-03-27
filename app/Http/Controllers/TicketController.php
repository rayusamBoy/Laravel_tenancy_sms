<?php

namespace App\Http\Controllers;

use App\Helpers\Usr;
use App\Http\Requests\Support\TicketCreate;
use App\Http\Requests\Support\TicketPropertiesCreate;
use App\Repositories\TicketRepo;
use App\Repositories\UserRepo;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;

class TicketController extends Controller implements HasMiddleware
{
    protected $ticket, $user;
    public function __construct(TicketRepo $ticket, UserRepo $user)
    {
        $this->ticket = $ticket;
        $this->user = $user;
    }

    public static function middleware(): array
    {
        return [
            new middleware('headSA', only: ['delete', 'lock_ticket', 'unlock_ticket', 'update_assignee']),
        ];
    }

    public function getPriorities()
    {
        return ['low', 'medium', 'high'];
    }

    public function getDepartments()
    {
        return ['help desk', 'sales', 'support', 'technical'];
    }

    public function getTenantUserTickets()
    {
        $where = ['tenant_id' => tenant('id'), 'user_id' => auth()->id()];
        $tickets = $this->ticket->getTickets($where);
        return $tickets;
    }

    /**
     * Display a listing of the tenant resource.
     */
    public function index()
    {
        $where = ['tenant_id' => tenant('id'), 'user_id' => auth()->id()];
        $tickets = $this->ticket->getTickets($where);

        $data['active_tickets'] = $tickets->where('is_archived', false);
        $data['archived_tickets'] = $tickets->where('is_archived', true);
        $data['departments'] = $this->getDepartments();
        $data['priorities'] = $this->getPriorities();
        $data['categories'] = $this->ticket->getCategories();
        $data['labels'] = $this->ticket->getLabels();

        return view('pages.support.tickets.index', $data);
    }

    /**
     * Display a listing of the ticket replies.
     */
    public function reply(string $ticket_id)
    {
        $ticket_id = Usr::decodeHash($ticket_id);

        $where_msg = ['user_id' => auth()->id(), 'ticket_id' => $ticket_id];
        $where_ticket = ['tenant_id' => tenant('id'), 'user_id' => auth()->id(), 'id' => $ticket_id];
        $tickets = $this->ticket->getTickets($where_ticket);

        if ($tickets->isEmpty())
            return back()->with('flash_warning', __('msg.rnf'));

        $data['ticket'] = $tk = $tickets->first();
        $tenant_user = json_decode($tk->tenant);
        $data['tenant_user'] = $tk->get_tenant_user($tenant_user->tenancy_db_name, $tk->user_id);
        $data['messages'] = $this->ticket->whereMessages($where_msg)->orderByDesc('created_at')->get();
        $data['priorities'] = $this->getPriorities();
        $data['categories'] = $this->ticket->getCategories();
        $data['labels'] = $this->ticket->getLabels()->pluck('name')->toArray();

        return view('pages.support.tickets.reply', $data);
    }

    public function answer(string $ticket_id)
    {
        $ticket_id = Usr::decodeHash($ticket_id);

        $where_msg = ['ticket_id' => $ticket_id];
        $where_ticket = Usr::userIsHead() ? ['id' => $ticket_id] : ['id' => $ticket_id, 'assigned_to' => auth()->id()];
        $tickets = $this->ticket->getTickets($where_ticket);

        if ($tickets->isEmpty())
            return back()->with('flash_warning', __('msg.rnf'));

        $data['ticket'] = $tk = $tickets->first();
        $tenant_user = json_decode($tk->tenant);
        $data['tenant_user'] = $tk->get_tenant_user($tenant_user->tenancy_db_name, $tk->user_id);
        $data['messages'] = $this->ticket->whereMessages($where_msg)->orderByDesc('created_at')->get();
        $data['priorities'] = $this->getPriorities();
        $data['categories'] = $this->ticket->getCategories();
        $data['labels'] = $this->ticket->getLabels()->pluck('name')->toArray();

        return view('pages.it_guy.support.tickets.answer', $data);
    }

    /**
     * Display a listing of the central resource.
     */
    public function index_central()
    {
        $where = ['assigned_to' => auth()->id(), 'is_archived' => false];
        $data['tickets'] = Usr::userIsHead() ? $this->ticket->getAll() : $this->ticket->getTickets($where)->where('status', '<>', 'closed');
        $data['users'] = $this->user->all();
        $data['categories'] = $this->ticket->getCategories();
        $data['labels'] = $this->ticket->getLabels();

        return view('pages.it_guy.support.tickets.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TicketCreate $request)
    {
        $ticket_data = $request->only(['department', 'priority', 'subject', 'category_id']);
        $ticket_data['tenant_id'] = tenant('id');
        $ticket_data['user_id'] = auth()->id();
        $ticket_data['labels_ids'] = serialize($request->labels_ids);

        try {
            $ticket = $this->ticket->createTicket($ticket_data);

            $is_from_tenant = Usr::tenancyInitilized();
            $message_data = ['ticket_id' => $ticket->id, 'user_id' => auth()->id(), 'body' => $request->message, 'is_from_tenant' => $is_from_tenant];
            $this->ticket->createMessage($message_data);
        } catch (\Exception $e) {
            $this->ticket->deleteTicket($ticket->id);
            return back()->with('flash_error', __('msg.store_error'));
        }

        return back()->with('flash_success', __('msg.store_ok'));
    }

    /**
     * Store a newly created message in storage.
     */
    public function store_message(Request $request)
    {
        Validator::make($request->toArray(), ['message' => 'required|string|max:400'])->validate();

        $ticket_id = $request->ticket_id;
        $is_from_tenant = Usr::tenancyInitilized();
        $message_data = ['ticket_id' => $ticket_id, 'user_id' => auth()->id(), 'body' => $request->message, 'is_from_tenant' => $is_from_tenant];

        $this->ticket->createMessage($message_data);
        $this->ticket->updateTicket($ticket_id, ['status' => 'replied']);

        return back()->with('flash_success', __('msg.store_ok'));
    }

    public function store_operator_message(Request $request)
    {
        Validator::make($request->toArray(), ['message' => 'required|string|max:400'])->validate();

        $ticket_id = $request->ticket_id;
        $is_from_tenant = Usr::tenancyInitilized();
        $message_data = ['ticket_id' => $ticket_id, 'user_id' => auth()->id(), 'body' => $request->message, 'is_from_tenant' => $is_from_tenant];

        $this->ticket->createMessage($message_data);
        $this->ticket->updateTicket($ticket_id, ['status' => 'answered']);

        return back()->with('flash_success', __('msg.store_ok'));
    }

    /**
     * Store a newly created properties in storage.
     */
    public function update_properties(TicketPropertiesCreate $request)
    {
        // Save categories
        if ($request->has('categories')) {
            $names = $request->input('categories.name');
            $descriptions = $request->input('categories.description');
            $visibilities = $request->input('categories.is_visible');

            foreach ($names as $index => $name) {
                $values = [
                    'description' => $descriptions[$index],
                    'is_visible' => $visibilities[$index],
                ];
                $this->ticket->updateOrCreateCategory(['name' => $name], $values);
            }
        }

        // Save labels
        if ($request->has('labels')) {
            $names = $request->input('labels.name');
            $descriptions = $request->input('labels.description');
            $visibilities = $request->input('labels.is_visible');

            foreach ($names as $index => $name) {
                $values = [
                    'description' => $descriptions[$index],
                    'is_visible' => $visibilities[$index],
                ];
                $this->ticket->updateOrCreateLabel(['name' => $name], $values);
            }
        }

        return Usr::jsonUpdateOk();
    }

    /**
     * Mark the ticket as closed.
     */
    public function close_ticket(string $ticket_id)
    {
        $user_tickets_ids = $this->getTenantUserTickets()->where('is_archived', false)->pluck('id')->toArray();
        $ticket_id = Usr::decodeHash($ticket_id);

        if (!in_array($ticket_id, $user_tickets_ids))
            return back()->with('flash_danger', __('msg.denied'));

        $this->ticket->updateTicket($ticket_id, ['status' => 'closed']);

        return back()->with('flash_success', __('msg.update_ok'));
    }

    /**
     * Archive the ticket.
     */
    public function archive_ticket(string $ticket_id)
    {
        $user_tickets_ids = $this->getTenantUserTickets()->where('is_archived', false)->pluck('id')->toArray();
        if (!in_array($ticket_id, $user_tickets_ids))
            return back()->with('flash_danger', __('msg.denied'));

        $ticket = $this->ticket->findTicket($ticket_id);
        if (!$ticket->is_closed())
            return back()->with('flash_warning', __('msg.close_ticket_before_archive'));

        $this->ticket->updateTicket($ticket_id, ['is_archived' => true]);

        return back()->with('flash_success', __('msg.update_ok'));
    }

    /**
     * Unarchive the ticket.
     */
    public function unarchive_ticket(string $ticket_id)
    {
        $user_tickets_ids = $this->getTenantUserTickets()->where('is_archived', true)->pluck('id')->toArray();
        if (!in_array($ticket_id, $user_tickets_ids))
            return back()->with('flash_danger', __('msg.denied'));

        $this->ticket->updateTicket($ticket_id, ['is_archived' => false]);

        return back()->with('flash_success', __('msg.update_ok'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $this->ticket->deleteTicket($id);

        return Usr::deleteOk('tickets.index_central');
    }

    public function lock_ticket(string $ticket_id)
    {
        $this->ticket->updateTicket($ticket_id, ['is_locked' => true]);

        return back()->with('flash_success', __('msg.update_ok'));
    }

    public function unlock_ticket(string $ticket_id)
    {
        $this->ticket->updateTicket($ticket_id, ['is_locked' => false]);

        return back()->with('flash_success', __('msg.update_ok'));
    }

    public function update_assignee(Request $request, $ticket_id)
    {
        $data = $request->only('assigned_to');
        $this->ticket->updateTicket($ticket_id, $data);

        return back()->with('flash_success', __('msg.update_ok'));
    }
}
