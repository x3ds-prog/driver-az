<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteVisit extends Model
{
    protected $fillable = [
        'visitor_hash',
        'ip_address',
        'country_code',
        'device_type',
        'device_brand_model',
        'os',
        'browser',
        'locale',
        'path',
        'referer',
        'is_bot',
        'visited_date',
    ];

    protected function casts(): array
    {
        return [
            'is_bot' => 'boolean',
            'visited_date' => 'date',
        ];
    }

    public function scopeHumans($query)
    {
        return $query->where('is_bot', false);
    }
}
