<?php

require 'vendor/autoload.php';
require 'classes/ZendeskAPI.php';
require 'classes/Ticket.php';

$subdomain = 'test-task';
$username = 'fallen.snitch@gmail.com';
$password = 'uaiP26y5eAnU67j';

$api = new ZendeskAPI($subdomain, $username, $password);
$tickets_data = $api->getTickets();

$tickets = [];
foreach ($tickets_data as $ticket_data) {
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

    $comments_data = $api->getTicketComments($ticket->id);

    if(isset($comments_data)){
        foreach ($comments_data as $comment_data) {
            $ticket->addComment($comment_data['body']);
        }
    }

    array_push($tickets, $ticket);
}

$csvFile = fopen('tickets.csv', 'w');
fputcsv($csvFile, ['Ticket ID', 'Description', 'Status', 'Priority', 'Agent ID', 'Agent Name', 'Agent Email', 'Contact ID', 'Contact Name', 'Contact Email', 'Group ID', 'Group Name', 'Company ID', 'Company Name', 'Comments']);

foreach ($tickets as $ticket) {
    fputcsv($csvFile, [
        $ticket->id, $ticket->description, $ticket->status, $ticket->priority,
        $ticket->agent_id, $ticket->agent_name, $ticket->agent_email,
        $ticket->contact_id, $ticket->contact_name, $ticket->contact_email,
        $ticket->group_id, $ticket->group_name,
        $ticket->company_id, $ticket->company_name,
        implode("\n", $ticket->comments)
    ]);
}

fclose($csvFile);
