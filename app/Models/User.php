<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'is_approved',
        'email_verified_at',
        'last_login_at',
        'last_login_ip',
        'profile_photo_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'is_active' => 'boolean',
            'is_approved' => 'boolean',
            'password' => 'hashed',
        ];
    }

    public function alumniProfile(): HasOne
    {
        return $this->hasOne(AlumniProfile::class);
    }

    public function employerProfile(): HasOne
    {
        return $this->hasOne(Employer::class);
    }

    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class, 'alumni_id');
    }

    public function jobMatches(): HasMany
    {
        return $this->hasMany(JobMatch::class, 'alumni_id');
    }

    public function employmentTrackings(): HasMany
    {
        return $this->hasMany(EmploymentTracking::class, 'alumni_id');
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'generated_by');
    }

    public function portalNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)->latest();
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeAlumni($query)
    {
        return $query->where('role', 'alumni');
    }

    public function scopeEmployers($query)
    {
        return $query->where('role', 'employer');
    }

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAlumni(): bool
    {
        return $this->role === 'alumni';
    }

    public function isEmployer(): bool
    {
        return $this->role === 'employer';
    }

    public function ensureEmployerProfile(): Employer
    {
        $profile = Employer::withTrashed()->firstOrNew([
            'user_id' => $this->id,
        ]);

        if (! $profile->exists) {
            $profile->fill([
                'company_name' => $this->nextAvailableEmployerCompanyName(),
                'country' => 'Philippines',
                'is_verified' => false,
            ]);
            $profile->save();
        } elseif ($profile->trashed()) {
            $profile->restore();
        }

        $this->setRelation('employerProfile', $profile);

        return $profile;
    }

    public function dashboardRoute(): string
    {
        return match ($this->role) {
            'admin' => route('admin.dashboard'),
            'employer' => route('employer.dashboard'),
            default => route('alumni.dashboard'),
        };
    }

    protected function nextAvailableEmployerCompanyName(): string
    {
        $base = Str::of($this->name)
            ->trim()
            ->limit(80, '')
            ->whenEmpty(fn () => Str::of("Employer Account {$this->id}"))
            ->append(" Company {$this->id}")
            ->toString();

        $candidate = $base;
        $suffix = 1;

        while (Employer::withTrashed()->where('company_name', $candidate)->exists()) {
            $candidate = "{$base} {$suffix}";
            $suffix++;
        }

        return $candidate;
    }
}
