<?php

namespace App\Models;

// use Filament\Models\Contracts\FilamentUser;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable //implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'bio',
        // 'social_links',
        'profile_photo',
    ];

    // protected $casts = [
    //     'social_links' => 'array',
    // ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // public function getFilamentAvatarUrl(): ?string
    // {
    //     if ($this->profile_photo && file_exists(storage_path('app/public/' . $this->profile_photo))) {
            
    //     }

    //     return 'https://ui-avatars.com/api/?name=' . urlencode($this->username);
    // }

    // public function canAccessPanel(\Filament\Panel $panel): bool
    // {
    //     return true;
    // }

}
