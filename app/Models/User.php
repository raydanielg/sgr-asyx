<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'phone', 'role', 'status', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function stations()
    {
        return $this->hasMany(Station::class, 'supervisor_id');
    }

    public function dailyReports()
    {
        return $this->hasMany(DailyReport::class, 'submitted_by');
    }

    public function reviewedReports()
    {
        return $this->hasMany(DailyReport::class, 'reviewed_by');
    }

    public function targets()
    {
        return $this->hasMany(Target::class, 'created_by');
    }

    public function isOwner(): bool { return $this->role === 'owner'; }
    public function isFinance(): bool { return $this->role === 'finance'; }
    public function isAdminManager(): bool { return $this->role === 'admin_manager'; }
    public function isSupervisor(): bool { return $this->role === 'supervisor'; }

    public function hasRole(string|array $roles): bool
    {
        return in_array($this->role, (array) $roles);
    }

    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'owner' => 'Company Owner',
            'finance' => 'Finance Officer',
            'admin_manager' => 'Admin Manager',
            'supervisor' => 'Supervisor',
            default => 'User',
        };
    }
}
