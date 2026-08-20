<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'name',
        'badge',
        'price',
        'period',
        'description',
        'features',
        'not_included',
        'popular',
        'cta_text',
        'cta_href',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'popular' => 'boolean',
            'is_active' => 'boolean',
            'features' => 'array',
            'not_included' => 'array',
            'display_order' => 'integer',
        ];
    }
}
