<?php

namespace Models;

class Comment
{
    public $body;
    public $ticket_id;

    public function __construct($body, $ticket_id)
    {
        $this->body = $body;
        $this->ticket_id = $ticket_id;
    }
}