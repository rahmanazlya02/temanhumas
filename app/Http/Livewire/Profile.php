<?php

namespace App\Http\Livewire;

use Illuminate\Support\HtmlString;
use JeffGreco13\FilamentBreezy\Pages\MyProfile as BaseProfile;

class Profile extends BaseProfile
{

    protected static ?string $slug = 'my-profile';

    protected function getUpdateProfileFormSchema(): array
    {
        $fields = parent::getUpdateProfileFormSchema();

        $fields[] = \Filament\Forms\Components\TextInput::make('whatsapp_number')
            ->label('WhatsApp Number')
            ->tel()
            ->prefix('+62')
            ->required()
            ->rules(['numeric', 'digits_between:10,12']);

    return $fields;
    }

    public function updateProfile()
    {
        $data = $this->updateProfileForm->getState();
        $loginColumnValue = $data[$this->loginColumn];
        unset($data[$this->loginColumn]);
        $this->user->update($data);
        $this->user->refresh();
        $this->updateProfileForm->fill([
            'name' => $this->user->name,
            'email' => $this->user->email,
            'whatsapp_number' => $this->user->whatsapp_number,
        ]);
        if ($loginColumnValue != $this->user->{$this->loginColumn}) {
            $this->user->newEmail($loginColumnValue);
        }
        $this->notify("success", __('filament-breezy::default.profile.personal_info.notify'));
    }

    public function resendPending(): void
    {
        $this->user->resendPendingEmailVerificationMail();
        $this->notify('success', __('Email verification sent'));
    }
}
