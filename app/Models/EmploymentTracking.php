<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmploymentTracking extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'employment_tracking';

    protected $fillable = [
        'alumni_id',
        'previous_status',
        'new_status',
        'previous_company',
        'new_company',
        'previous_title',
        'new_title',
        'changed_by',
        'change_date',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'change_date' => 'datetime',
        ];
    }

    public function alumni(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alumni_id');
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
