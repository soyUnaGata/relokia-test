<?php

namespace Models;

class Ticket
{
    public $id;
    public $description;
    public $status;
    public $priority;
    public $agent_id;
    public $contact_id;
    public $group_id;
    public $company_id;

    public static function createFromArray($data): Ticket
    {
        $ticket = new Ticket();

        $ticket->id = $data['id'];
        $ticket->description = $data['description'];
        $ticket->status = $data['status'];
        $ticket->priority = $data['priority'];
        $ticket->agent_id = $data['assignee_id'];
        $ticket->contact_id = $data['requester_id'];
        $ticket->group_id = $data['group_id'];
        $ticket->company_id = $data['organization_id'];

        return $ticket;
    }
}