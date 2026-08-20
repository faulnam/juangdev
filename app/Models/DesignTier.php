<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tagline',
        'description',
        'price',
        'badge',
        'features',
        'is_popular',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'is_popular' => 'boolean',
            'features' => 'array',
            'display_order' => 'integer',
        ];
    }
}
