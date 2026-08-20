<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'name',
        'tagline',
        'description',
        'icon',
        'base_price',
        'starting_price',
        'delivery_time',
        'popular',
        'features',
        'technologies',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'popular' => 'boolean',
            'is_active' => 'boolean',
            'features' => 'array',
            'technologies' => 'array',
            'base_price' => 'integer',
            'display_order' => 'integer',
        ];
    }
}
