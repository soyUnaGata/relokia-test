<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;

use \Services\Config;
use \Services\CSVReport;
use \Services\ZendeskAPI;


$default_start_date = 946677600;
$api = new ZendeskAPI(Config::$subdomain, Config::$username, Config::$password);

function array_find($array, $callback)
{
    foreach ($array as $item) {
        if (call_user_func($callback, $item) === true)
            return $item;
    }
    return null;
}

$comments = $api->getComments($default_start_date);
$tickets = $api->exportAllTickets($default_start_date);
$users = $api->exportAllUsers($default_start_date);
$groups = $api->getGroups();
$organizations = $api->getOrganizations();

$report = new CSVReport('tickets.csv', ['Ticket ID', 'Description', 'Status', 'Priority', 'Agent ID', 'Agent Name', 'Agent Email', 'Contact ID', 'Contact Name', 'Contact Email', 'Group ID', 'Group Name', 'Company ID', 'Company Name', 'Comments']);

foreach ($tickets as $ticket) {
    $tickets_comments = array_filter($comments, function ($c) use ($ticket) {
        return $c->ticket_id === $ticket->id;
    });

    $agent = array_find($users, function ($user) use ($ticket) {
        return $user->id === $ticket->agent_id;
    });

    $contact = array_find($users, function ($user) use ($ticket) {
        return $user->id === $ticket->contact_id;
    });

    $company = array_find($organizations, function ($org) use ($ticket) {
        return $org->id === $ticket->organization_id;
    });

    $group = array_find($groups, function ($gr) use ($ticket) {
        return $gr->id === $ticket->group_id;
    });

    $report->AddRow($ticket, $agent, $contact, $company, $group, $tickets_comments);
}

$report->save();