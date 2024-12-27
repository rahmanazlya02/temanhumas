<?php

namespace App\Listeners;

use App\Events\TicketUpdated;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TicketUpdatedListener
{
    public function handle(TicketUpdated $event)
    {
        $ticket = $event->ticket;

        // Cek apakah ticket memiliki responsible_id dan project_id
        if ($ticket->responsible_id && $ticket->project_id) {
            // Ambil project terkait
            $project = $ticket->project;
            $responsibleUser = User::find($ticket->responsible_id);

            // Cek apakah user sudah memiliki salah satu role yang diinginkan
            if ($responsibleUser->hasRole('Koordinator Subtim') || $responsibleUser->hasRole('Ketua Tim Humas')) {
                // Jika role yang dimiliki sudah benar, tidak perlu melakukan apa-apa
                return;
            }

            // Jika user tidak memiliki salah satu role tersebut, set role menjadi "anggota"
            $roleName = 'anggota';

            // Sinkronkan user responsible dengan project tanpa membuat role baru
            $project->users()->syncWithoutDetaching([
                $ticket->responsible_id => ['role' => $roleName], // Gunakan nama role yang sudah ada
            ]);
        }
    }
}
