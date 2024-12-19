<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Services\FonnteService;
use Illuminate\Http\Client\Request;
use App\Models\User;

class WhatsappController extends Controller
{
    protected $fonnteService;

    public function __construct(FonnteService $fonnteService)
    {
        $this->fonnteService = $fonnteService;
    }

    public function sendMessage(Ticket $task)
    {


        // $to = '+6289630912427'; // Nomor tujuan dengan format internasional
        // $message = 'dari kiel';
        // $response = $this->fonnteService->sendMessage($to, $message);
        // $responsible_id = $task['responsible_id'];
        $responsible_user = $task->responsible;
        $responsible_project = $task->project;

        if ($responsible_user) {

            $assignedUser = $responsible_user;
            $fonnteService = new FonnteService();
            $message = "Halo {$assignedUser->name},\n\n" .
                "Anda telah ditugaskan untuk:\n" .
                "Nama Tugas: {$task->name}\n" .
                "Nama Project: {$responsible_project->name}\n" .
                "Deadline: {$task->deadline}\n\n" .

                "Untuk detail tugas lebih lanjut, Silakan cek pada tautan http://temanhumas.xath.site\n\n" .
                "Selamat mengerjakan tugas dan harap selesaikan tugasmu tepat waktu ya...!☺.";


            $whatsapp = '+62' . $assignedUser->whatsapp_number;

            $response = $fonnteService->sendMessage($whatsapp, $message);

            return response()->json($response);
        }
        return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
    }
}
