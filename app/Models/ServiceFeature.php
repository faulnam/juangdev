<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'price',
        'popular',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'popular' => 'boolean',
            'is_active' => 'boolean',
            'price' => 'integer',
            'display_order' => 'integer',
        ];
    }
}
