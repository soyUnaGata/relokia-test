<?php

namespace Services;

class CSVReport
{
    private $csvFile;
    private $saved = false;

    public function __construct($filename, $headers)
    {
        $this->csvFile = fopen($filename, 'w');
        fputcsv($this->csvFile, $headers);
    }

    public function AddRow($ticket, $agent, $contact, $company, $group, $comments)
    {
        if ($this->saved) {
            throw new \Exception('File has been already saved before adding data.');
        }

        $str_comments = implode("\n", array_map(function ($c) {
            return $c->body;
        }, $comments));

        fputcsv($this->csvFile, [
            $ticket->id, $ticket->description, $ticket->status, $ticket->priority,
            $agent->id, $agent->name, $agent->email,
            $contact->id, $contact->name, $contact->email,
            $group->id, $group->name,
            $company->id, $company->name,
            $str_comments
        ]);
    }

    function save()
    {
        if ($this->saved) {
            throw new \Exception('File has been already saved.');
        }

        fclose($this->csvFile);
        $this->saved = true;
    }
}