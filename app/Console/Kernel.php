<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Ticket;
use App\Services\FonnteService;
use Illuminate\Support\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function () {
            $now = Carbon::now();
    
            // Ambil semua tugas dengan waktu pengingat sesuai jadwal
            $tasks = Ticket::whereNotNull('reminder') // Pengingat diatur
                ->where('reminder', '<=', $now) // Sudah waktunya dikirim
                ->get();
    
            foreach ($tasks as $task) {
                $responsibleUser = $task->responsible;
    
                if ($responsibleUser) {
                    $fonnteService = new FonnteService();
                    $message = "Halo {$responsibleUser->name},\n\n" .
                        "🌟 Pengingat penting nih untuk tugas kamu! 🌟\n\n" .
                        "📝 *Nama Tugas*: {$task->name}\n" .
                        "⏰ *Deadline*: {$task->deadline}\n\n" .
                        "📌 Yuk, cek detail tugasnya di sini: https://temanhumas.xath.site\n\n" .
                        "Semangat ya! Pastikan tugas ini selesai tepat waktu. Kami percaya kamu bisa! 🚀💼\n\n" .
                        "Salam hangat,\nTim Teman Humas";
    
                    $whatsapp = '+62' . $responsibleUser->whatsapp_number;
                    
                    $task->update(['reminder' => null]);
                    
                    // Kirim pesan melalui Fonnte
                    $response = $fonnteService->sendMessage($whatsapp, $message);

                    if ($response['status'] === 'success') {
                        // Hapus waktu pengingat untuk mencegah pengiriman ulang
                    }
                }
            }
        })->everyMinute(); // Sesuaikan interval jadwal

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}