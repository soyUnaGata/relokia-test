<?php

namespace Services;
use GuzzleHttp\Client;

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

    public function getTickets()
    {
        $response = $this->client->request('GET', 'tickets.json');
        return json_decode($response->getBody()->getContents(), true)['tickets'];
    }

    public function getTicketComments($ticket_id)
    {
        $response = $this->client->request('GET', "tickets/{$ticket_id}/comments.json");
        return json_decode($response->getBody()->getContents(), true)['comments'];
    }
}