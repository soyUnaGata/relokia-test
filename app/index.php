<?php

require 'vendor/autoload.php';

use Models\Comment;
use Models\Ticket;
use Services\Config;
use Services\CSVDataManager;
use Services\ZendeskAPI;

$api = new ZendeskAPI(Config::$subdomain, Config::$username, Config::$password);
$csv = new CSVDataManager();

$tickets_data = $api->getTickets();

$tickets = [];
foreach ($tickets_data as $ticket_data) {
    $ticket = Ticket::createFromArray($ticket_data);

    if(!$ticket->has_comments){
        continue;
    }

    $comments_data = $api->getTicketComments($ticket->id);
    if(isset($comments_data) && is_array($comments_data)){
        $comments = array_map(function ($data) { return Comment::create($data); }, $comments_data);
        $ticket->addComments($comments);
    }

    $tickets[] = $ticket;
}

$csv->saveTickets('tickets.csv', $tickets);