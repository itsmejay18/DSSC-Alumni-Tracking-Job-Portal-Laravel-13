<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'company_name',
        'company_registration_number',
        'company_logo_path',
        'industry_id',
        'company_size',
        'website',
        'phone',
        'address_line1',
        'address_line2',
        'city',
        'province',
        'country',
        'postal_code',
        'verification_documents',
        'is_verified',
        'verification_date',
        'verified_by',
    ];

    protected function casts(): array
    {
        return [
            'verification_documents' => 'array',
            'is_verified' => 'boolean',
            'verification_date' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }

    public function jobs(): HasMany
    {
        return $this->hasMany(Job::class);
    }

    public function verifiedByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function getFullAddressAttribute(): string
    {
        return implode(', ', array_filter([
            $this->address_line1,
            $this->address_line2,
            $this->city,
            $this->province,
            $this->country,
            $this->postal_code,
        ]));
    }
}
