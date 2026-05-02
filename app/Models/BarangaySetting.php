<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarangaySetting extends Model
{
    protected static function booted(): void
    {
        static::creating(function (BarangaySetting $model): void {
            if (static::query()->exists()) {
                throw new \RuntimeException('Only one barangay_settings record is allowed.');
            }
        });
    }

    protected $fillable = [
        'barangay_name',
        'municipality',
        'province',
        'captain_name',
        'secretary_name',
        'logo_path',
        'contact_number',
        'email',
        'office_address',
    ];

    /**
     * Single row used across the portal (print templates, branding, etc.).
     */
    public static function current(): self
    {
        $row = static::query()->first();

        if ($row !== null) {
            return $row;
        }

        return static::create([
            'barangay_name' => 'Alfonso XIII',
            'municipality' => 'Quezon',
            'province' => 'Palawan',
        ]);
    }
}
