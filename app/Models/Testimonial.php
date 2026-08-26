<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'company',
        'content',
        'rating',
        'featured',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'featured' => 'boolean',
            'display_order' => 'integer',
        ];
    }
}
