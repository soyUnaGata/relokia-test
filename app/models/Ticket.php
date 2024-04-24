<?php

namespace Models;

class Ticket
{
    public $id;
    public $description;
    public $status;
    public $priority;
    public $agent_id;
    public $agent_name;
    public $agent_email;
    public $contact_id;
    public $contact_name;
    public $contact_email;
    public $group_id;
    public $group_name;
    public $company_id;
    public $company_name;
    public $has_comments;
    public $comments = [];

    public static function createFromArray($ticket_data) : Ticket
    {
        $ticket = new Ticket();

        $ticket->id = $ticket_data['id'];
        $ticket->description = $ticket_data['description'];
        $ticket->status = $ticket_data['status'];
        $ticket->priority = $ticket_data['priority'];
        $ticket->agent_id = $ticket_data['assignee_id'];
        $ticket->agent_name = $ticket_data['agent_name'];
        $ticket->agent_email = $ticket_data['agent_email'];
        $ticket->contact_id = $ticket_data['contact_id'];
        $ticket->contact_name = $ticket_data['contact_name'];
        $ticket->contact_email = $ticket_data['contact_email'];
        $ticket->group_id = $ticket_data['group_id'];
        $ticket->group_name = $ticket_data['group_name'];
        $ticket->company_id = $ticket_data['company_id'];
        $ticket->company_name = $ticket_data['company_name'];
        $ticket->has_comments = $ticket_data['is_public'];

        return $ticket;
    }


    public function addComments($comments)
    {
        $this->comments = array_merge($this->comments, $comments);
    }
}