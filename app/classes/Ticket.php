<?php
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
    public $comments = [];

    public function addComment($comment)
    {
        $this->comments[] = $comment;
    }
}