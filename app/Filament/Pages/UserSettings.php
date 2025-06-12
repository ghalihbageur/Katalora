<?php

namespace App\Filament\Pages;

use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Concerns\InteractsWithForms;

class UserSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static string $view = 'filament.pages.user-settings';

    public ?array $data = []; // Ini penting agar binding state tidak error

    public function mount(): void
    {
        $user = Auth::user();

        $this->form->fill([
            'name' => $user->name,
            'username' => $user->username,
            'email' => $user->email,
            'bio' => $user->bio,
            'profile_photo' => $user->profile_photo,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data') // Ini penting! Semua input akan di-bind ke $this->data
            ->schema([
                TextInput::make('name')->label('Nama')->required(),
                TextInput::make('username')->label('Username')->required(),
                TextInput::make('email')->label('Email')->email()->required(),
                Textarea::make('bio')->label('Bio'),
                FileUpload::make('profile_photo')
                    ->label('Foto Profil')
                    ->directory('profile-photos')
                    ->image()
                    ->visibility('public')
                    ->disk('public'),
            ]);
    }

    public function submit(): void
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $data = $this->form->getState();

        $user->update([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'bio' => $data['bio'],
            'profile_photo' => $data['profile_photo'],
        ]);

        Log::info('Form Data:', $data); // Tambahkan ini

        Notification::make()
            ->title('Pengaturan berhasil disimpan.')
            ->success()
            ->send();
    }

    protected static bool $shouldRegisterNavigation = false;

}
