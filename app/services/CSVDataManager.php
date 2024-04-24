<?php

namespace Services;

class CSVDataManager
{
    function saveTickets($filename, $tickets)
    {
        $csvFile = fopen($filename, 'w');
        fputcsv($csvFile, ['Ticket ID', 'Description', 'Status', 'Priority', 'Agent ID', 'Agent Name', 'Agent Email', 'Contact ID', 'Contact Name', 'Contact Email', 'Group ID', 'Group Name', 'Company ID', 'Company Name', 'Comments']);

        foreach ($tickets as $ticket) {
            fputcsv($csvFile, [
                $ticket->id, $ticket->description, $ticket->status, $ticket->priority,
                $ticket->agent_id, $ticket->agent_name, $ticket->agent_email,
                $ticket->contact_id, $ticket->contact_name, $ticket->contact_email,
                $ticket->group_id, $ticket->group_name,
                $ticket->company_id, $ticket->company_name,
                implode("\n", array_map(function ($c) { return $c->body; }, $ticket->comments))
            ]);
        }

        fclose($csvFile);
    }
}