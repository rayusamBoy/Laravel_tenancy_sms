<?php

namespace App\Repositories;

use App\Models\Ticket;
use App\Models\TicketCategory;
use App\Models\TicketLabel;
use App\Models\TicketMessage;

class TicketRepo
{
    // Tickets
    public function getAll()
    {
        return Ticket::all();
    }

    public function findTicket($id)
    {
        return Ticket::find($id);
    }

    public function createTicket($attributes)
    {
        return Ticket::create($attributes);
    }

    public function deleteTicket($id)
    {
        return Ticket::destroy($id);
    }

    public function getTickets($where)
    {
        return Ticket::where($where)->get();
    }

    public function updateTicket($id, $attributes)
    {
        return Ticket::where('id', $id)->update($attributes);
    }

    // Ticket messages
    public function createMessage($attributes)
    {
        return TicketMessage::create($attributes);
    }

    public function whereMessages($where)
    {
        return TicketMessage::where($where);
    }

    // Categories
    public function getCategories()
    {
        return TicketCategory::all();
    }

    public function updateOrCreateCategory($attributes, $values)
    {
        return TicketCategory::updateOrCreate($attributes, $values);
    }

    public function deleteCategoryWhereNotIn($column, $values)
    {
        return TicketCategory::whereNotIn($column, $values)->delete();
    }

    // Labels
    public function getLabels()
    {
        return TicketLabel::all();
    }

    public function updateOrCreateLabel($attributes, $values)
    {
        return TicketLabel::updateOrCreate($attributes, $values);
    }

    public function deleteLabelWhereNotIn($column, $values)
    {
        return TicketLabel::whereNotIn($column, $values)->delete();
    }
}
