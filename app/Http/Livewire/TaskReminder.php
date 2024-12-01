<?php

namespace App\Http\Livewire;

use App\Models\Reminder;
use Livewire\Component;

class TaskReminder extends Component
{
    public $record;
    public $reminderTime;
    public $reminderId;

    // Menyiapkan data saat komponen dimuat
    public function mount($record)
    {
        $this->record = $record;

        // Cek apakah sudah ada pengingat untuk tugas ini
        $reminder = Reminder::where('ticket_id', $this->record->id)->first();

        // Jika ada, set nilai reminder time dan ID
        if ($reminder) {
            $this->reminderTime = $reminder->reminder_time;
            $this->reminderId = $reminder->id;
        } else {
            $this->reminderTime = '19:45'; // Default reminder time
        }
    }

    // Fungsi untuk menyimpan pengingat
    public function saveReminder()
    {
        // Jika pengingat sudah ada, update, jika tidak buat baru
        if ($this->reminderId) {
            $reminder = Reminder::find($this->reminderId);
            $reminder->update([
                'reminder_time' => $this->reminderTime,
            ]);
        } else {
            // Membuat pengingat baru
            Reminder::create([
                'ticket_id' => $this->record->id,
                'reminder_time' => $this->reminderTime,
                'status' => 'active', // Misalnya, status aktif
            ]);
        }

        session()->flash('message', 'Reminder saved successfully!');
    }

    public function render()
    {
        return view('livewire.task-reminder');
    }
}
