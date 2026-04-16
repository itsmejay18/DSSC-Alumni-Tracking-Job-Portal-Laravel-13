<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'setting_key',
        'setting_value',
        'setting_type',
        'group',
        'description',
        'is_public',
    ];

    protected function casts(): array
    {
        return [
            'is_public' => 'boolean',
        ];
    }

    public function typedValue(): mixed
    {
        return match ($this->setting_type) {
            'integer' => (int) $this->setting_value,
            'boolean' => filter_var($this->setting_value, FILTER_VALIDATE_BOOL),
            'json' => json_decode((string) $this->setting_value, true),
            default => $this->setting_value,
        };
    }

    public static function value(string $key, mixed $default = null): mixed
    {
        $setting = static::query()->where('setting_key', $key)->first();

        return $setting ? $setting->typedValue() : $default;
    }

    public static function publicGrouped(): Collection
    {
        return static::query()
            ->where('is_public', true)
            ->get()
            ->groupBy('group')
            ->map(fn (Collection $group) => $group->mapWithKeys(
                fn (Setting $setting) => [$setting->setting_key => $setting->typedValue()]
            ));
    }
}
