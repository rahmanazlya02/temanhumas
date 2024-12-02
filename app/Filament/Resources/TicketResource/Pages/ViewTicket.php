<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Exports\TicketHoursExport;
use App\Filament\Resources\TicketResource;
use App\Models\Activity;
use App\Models\TicketComment;
use App\Models\TicketHour;
use App\Models\TicketSubscriber;
use App\Models\Reminder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Carbon;

class ViewTicket extends ViewRecord implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = TicketResource::class;

    protected function getTitle(): string
    {
        return 'View Task';
    }

    protected static string $view = 'filament.resources.tickets.view';

    public string $tab = 'comments';

    protected $listeners = ['doDeleteComment'];

    public $selectedCommentId;

    public function mount($record): void
    {
        parent::mount($record);
        $this->form->fill();
    }

    protected function getActions(): array
    {
        return [
            Actions\Action::make('setReminder')
                ->label(__('Set Reminder'))
                ->color('warning')
                ->icon('heroicon-o-bell')
                ->button()
                ->form([
                    DatePicker::make('reminderDate')
                        ->label(__('Reminder Date'))
                        ->minDate(now()->format('Y-m-d'))
                        ->maxDate($this->record->deadline) // Asumsikan ada atribut `deadline` di tiket
                        ->required(),
                    TimePicker::make('reminderTime')
                        ->label(__('Reminder Time'))
                        ->required(),
                ])
                ->action('saveReminder'),
            Actions\EditAction::make(),

        ];
    }

    public function saveReminder(array $data): void
    {
        // Gabungkan tanggal dan waktu menggunakan format yang benar
        $reminderDateTime = Carbon::createFromFormat(
            'Y-m-d H:i',
        $data['reminderDate'] . ' ' . Carbon::parse($data['reminderTime'])->format('H:i')
    );

        // Validasi agar tidak melebihi deadline
        if ($reminderDateTime->greaterThan($this->record->deadline)) {
            Notification::make()
                ->title(__('Invalid Reminder'))
                ->body(__('The reminder date and time cannot exceed the deadline.'))
                ->warning()
                ->send();

            return;
        }
        // Simpan pengingat ke database (opsional)
        Reminder::updateOrCreate(
            [
                'user_id' => auth()->user()->id,
                'ticket_id' => $this->record->id,
            ],
            [
                'reminder_at' => $reminderDateTime,
            ]
        );

        Notification::make()
            ->title(__('Reminder Set'))
            ->body(__('You have successfully set a reminder.'))
            ->success()
            ->send();
    }

    public function selectTab(string $tab): void
    {
        $this->tab = $tab;
    }

    protected function getFormSchema(): array
    {
        return [
            RichEditor::make('comment')
                ->disableLabel()
                ->placeholder(__('Type a new comment'))
                ->required()
        ];
    }

    public function submitComment(): void
    {
        $data = $this->form->getState();
        if ($this->selectedCommentId) {
            TicketComment::where('id', $this->selectedCommentId)
                ->update([
                    'content' => $data['comment']
                ]);
        } else {
            TicketComment::create([
                'user_id' => auth()->user()->id,
                'ticket_id' => $this->record->id,
                'content' => $data['comment']
            ]);
        }
        $this->record->refresh();
        $this->cancelEditComment();
        $this->notify('success', __('Comment saved'));
    }

    public function isAdministrator(): bool
    {
        return $this->record
                ->project
                ->users()
                ->where('users.id', auth()->user()->id)
                ->where('role', 'administrator')
                ->count() != 0;
    }

    public function editComment(int $commentId): void
    {
        $this->form->fill([
            'comment' => $this->record->comments->where('id', $commentId)->first()?->content
        ]);
        $this->selectedCommentId = $commentId;
    }

    public function deleteComment(int $commentId): void
    {
        Notification::make()
            ->warning()
            ->title(__('Delete confirmation'))
            ->body(__('Are you sure you want to delete this comment?'))
            ->actions([
                Action::make('confirm')
                    ->label(__('Confirm'))
                    ->color('danger')
                    ->button()
                    ->close()
                    ->emit('doDeleteComment', compact('commentId')),
                Action::make('cancel')
                    ->label(__('Cancel'))
                    ->close()
            ])
            ->persistent()
            ->send();
    }

    public function doDeleteComment(int $commentId): void
    {
        TicketComment::where('id', $commentId)->delete();
        $this->record->refresh();
        $this->notify('success', __('Comment deleted'));
    }

    public function cancelEditComment(): void
    {
        $this->form->fill();
        $this->selectedCommentId = null;
    }
}