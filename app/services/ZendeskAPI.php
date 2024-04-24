<?php

namespace Services;

use GuzzleHttp\Client;

use \Models\Comment;
use \Models\Group;
use \Models\Organization;
use \Models\Ticket;
use \Models\User;

class ZendeskAPI
{
    private $client;
    private $subdomain;
    private $username;
    private $password;

    public function __construct($subdomain, $username, $password)
    {
        $this->subdomain = $subdomain;
        $this->username = $username;
        $this->password = $password;
        $this->client = new Client([
            'base_uri' => "https://$subdomain.zendesk.com/api/v2/",
            'auth' => [$username, $password]
        ]);
    }

    public function exportAllTickets($start_date): array
    {
        $response = $this->client->request('GET', 'incremental/tickets/cursor.json?start_time=' . $start_date);
        $data = json_decode($response->getBody(), true);
        $cursor = $data['after_cursor'];
        $end_of_stream = $data['end_of_stream'];
        $tickets = array_map(function ($t) {
            return Ticket::createFromArray($t);
        }, $data['tickets']);

        while (!$end_of_stream) {

            $response = $this->client->request('GET', 'incremental/tickets/cursor.json?cursor=' . $cursor . 'start_time=' . $start_date);
            $data = json_decode($response->getBody(), true);
            $cursor = $data['after_cursor'];
            $end_of_stream = $data['end_of_stream'];
            $results = array_map(function ($t) {
                return Ticket::createFromArray($t);
            }, $data['tickets']);

            $tickets = array_merge($tickets, $results);
        }

        return $tickets;
    }

    public function exportAllUsers($start_date): array
    {
        $response = $this->client->request('GET', 'incremental/users/cursor.json?start_time=' . $start_date);
        $data = json_decode($response->getBody(), true);
        $cursor = $data['after_cursor'];
        $end_of_stream = $data['end_of_stream'];
        $users = array_map(function ($t) {
            return User::createFromArray($t);
        }, $data['users']);

        while (!$end_of_stream) {

            $response = $this->client->request('GET', 'incremental/users/cursor.json?cursor=' . $cursor . 'start_time=' . $start_date);
            $data = json_decode($response->getBody(), true);
            $cursor = $data['after_cursor'];
            $end_of_stream = $data['end_of_stream'];
            $results = array_map(function ($t) {
                return User::createFromArray($t);
            }, $data['users']);

            $users = array_merge($users, $results);
        }

        return $users;
    }

    public function getComments($start_date): array
    {
        $ticket_events = $this->getTicketEvents($start_date);

        $comments = [];
        foreach ($ticket_events as $ticket_event) {
            $child_events = $ticket_event['child_events'];
            $child_events_comments = array_filter($child_events, function ($ce) {
                return $ce['event_type'] === "Comment";
            });

            foreach ($child_events_comments as $child_event_comment) {
                $comment = new Comment($child_event_comment['body'], $ticket_event['ticket_id']);
                $comments[] = $comment;
            }
        }

        return $comments;
    }

    public function getOrganizations(): array
    {
        $response = $this->client->request('GET', 'organizations.json');
        $results = json_decode($response->getBody()->getContents(), true)['organizations'];

        return array_map(function ($data) {
            return Organization::createFromArray($data);
        }, $results);
    }

    public function getGroups(): array
    {
        $response = $this->client->request('GET', 'groups.json');
        $results = json_decode($response->getBody()->getContents(), true)['groups'];

        return array_map(function ($data) {
            return Group::createFromArray($data);
        }, $results);
    }

    private function getTicketEvents($start_date): array
    {
        $response = $this->client->request('GET', 'incremental/ticket_events.json?start_time=' . $start_date . '&include=comment_events');
        return json_decode($response->getBody()->getContents(), true)['ticket_events'];
    }
}