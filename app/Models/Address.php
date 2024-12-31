<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'user_id',
        'country',
        'city',
        'neighborhood',
        'building_no',
        'apartment_no',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFullAddressAttribute(): string
    {
        return implode(', ', array_filter([
            $this->country,
            $this->city,
            $this->neighborhood,
            $this->building_no ? 'Building No: ' . $this->building_no : null,
            $this->apartment_no ? 'Apartment No: ' . $this->apartment_no : null,
        ]));
    }
}