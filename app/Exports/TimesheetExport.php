<?php

namespace App\Exports;

use App\Models\Ticket;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TimesheetExport implements FromCollection, WithHeadings
{
    protected array $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function headings(): array
    {
        return [
            'Project',
            'Task',
            'Details',
            'Owner',
            'Responsible',
            'Type',
            'Status'
        ];
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $collection = collect();

        // Mengambil data Ticket dengan eager loading untuk mengoptimalkan query
        $tickets = Ticket::with(['type', 'status', 'owner', 'responsible', 'project'])
            ->where('owner_id', auth()->user()->id) // Menggunakan owner_id
            ->whereBetween('created_at', [$this->params['start_date'], $this->params['end_date']])
            ->get();

        foreach ($tickets as $ticket) {
            $collection->push([
                'project' => $ticket->project?->name ?? '-', // Nama proyek
                'task' => $ticket->name, // Nama tiket diubah menjadi Task
                'details' => strip_tags($ticket->content), // Detil tiket
                'owner' => $ticket->owner?->name ?? '-', // Nama owner
                'responsible' => $ticket->responsible?->name ?? '-', // Nama responsible
                'type' => $ticket->type?->name ?? '-', // Nama tipe tiket
                'status' => $ticket->status?->name ?? '-', // Nama status tiket
            ]);
        }

        return $collection;
    }
}
