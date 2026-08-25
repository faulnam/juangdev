<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'client',
        'client_industry',
        'duration',
        'category',
        'package_tier',
        'description',
        'overview',
        'key_features',
        'gallery',
        'image_url',
        'live_url',
        'technologies',
        'featured',
        'is_boilerplate',
        'sold_count',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'is_boilerplate' => 'boolean',
            'sold_count' => 'integer',
            'technologies' => 'array',
            'key_features' => 'array',
            'gallery' => 'array',
            'display_order' => 'integer',
        ];
    }
}
