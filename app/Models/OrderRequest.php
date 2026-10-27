<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRequest extends Model
{
    public const STATUSES = ['new', 'processing', 'completed', 'cancelled'];

    public const TARIFF_ZONES = ['baku_city', 'settlements', 'sumqayit_villages', 'sumqayit_city'];

    protected $fillable = [
        'name',
        'phone',
        'pickup_address',
        'destination_address',
        'tariff_zone',
        'car_type',
        'comment',
        'locale',
        'status',
        'ip_address',
        'country_code',
        'device_name',
        'user_agent',
    ];
}
