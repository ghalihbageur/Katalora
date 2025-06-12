<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Forms;
use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Support\Facades\Hash;

class Register extends BaseRegister
{
    public function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->unique(User::class, 'name')
                    ->maxLength(20),

                Forms\Components\TextInput::make('username')
                    ->required()
                    ->unique(User::class, 'username')
                    ->maxLength(20),

                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(User::class, 'email'),

                Forms\Components\TextInput::make('password')
                    ->password()
                    ->required()
                    ->minLength(8)
                    ->same('password_confirmation'),

                Forms\Components\TextInput::make('password_confirmation')
                    ->password()
                    ->label('Konfirmasi Password')
                    ->required(),

                Forms\Components\Textarea::make('bio')
                    ->label('Bio')
                    ->rows(3)
                    ->maxLength(250),

                Forms\Components\FileUpload::make('profile_photo')
                    ->label('Foto Profil')
                    ->image()
                    ->imageEditor()
                    ->imageCropAspectRatio('1:1')
                    ->directory('profile-photos')
                    ->maxSize(1024),

                // Forms\Components\KeyValue::make('social_links')
                //     ->label('Media Sosial')
                //     ->keyLabel('Platform (misal: Instagram)')
                //     ->valueLabel('Link URL'),

                // Jika kamu ingin role dan verified otomatis di-set tanpa form
            ]);
    }

    protected function handleRegistration(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'bio' => $data['bio'] ?? null,
            'profile_photo' => $data['profile_photo'] ?? null,
            // 'social_links' => json_encode($data['social_links'] ?? []),
            'role' => 'penulis',
            'is_verified' => false,
            'total_views' => 0,
            'total_interaksi' => 0,
        ]);
    }
}


// namespace App\Filament\Pages;

// use Filament\Pages\Page;

// class Register extends Page
// {
//     protected static ?string $navigationIcon = 'heroicon-o-document-text';

//     protected static string $view = 'filament.pages.register';
// }
