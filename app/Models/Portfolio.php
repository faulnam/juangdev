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
        'description',
        'overview',
        'key_features',
        'gallery',
        'image_url',
        'live_url',
        'technologies',
        'featured',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'featured' => 'boolean',
            'technologies' => 'array',
            'key_features' => 'array',
            'gallery' => 'array',
            'display_order' => 'integer',
        ];
    }
}
