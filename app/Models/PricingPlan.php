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
        'original_price',
        'discount_percent',
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
            'discount_percent' => 'integer',
            'display_order' => 'integer',
        ];
    }

    public function getHasDiscountAttribute(): bool
    {
        return !empty($this->original_price) || ($this->discount_percent && $this->discount_percent > 0);
    }
}
