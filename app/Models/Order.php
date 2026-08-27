<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'invoice_number',
        'token',
        'customer_name',
        'customer_email',
        'customer_phone',
        'project_name',
        'service_name',
        'package_name',
        'addons',
        'original_amount',
        'discount_amount',
        'total_amount',
        'dp_amount',
        'remaining_amount',
        'payment_scheme',
        'payment_status',
        'project_status',
        'pakasir_trx_id',
        'notes',
        'attachment_path',
        'attachment_name',
        'attachment_size',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'addons' => 'array',
        'original_amount' => 'integer',
        'discount_amount' => 'integer',
        'total_amount' => 'integer',
        'dp_amount' => 'integer',
        'remaining_amount' => 'integer',
        'attachment_size' => 'integer',
    ];

    public function getHasDiscountAttribute(): bool
    {
        return ($this->discount_amount && $this->discount_amount > 0) || 
               ($this->original_amount && $this->original_amount > $this->total_amount);
    }

    public function getFormattedOriginalAmountAttribute()
    {
        $amt = $this->original_amount ?: $this->total_amount;
        return 'Rp ' . number_format($amt, 0, ',', '.');
    }

    public function getFormattedDiscountAmountAttribute()
    {
        return 'Rp ' . number_format($this->discount_amount ?: 0, 0, ',', '.');
    }

    public function getFormattedTotalAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    public function getFormattedDpAttribute()
    {
        return 'Rp ' . number_format($this->dp_amount, 0, ',', '.');
    }

    public function getFormattedRemainingAttribute()
    {
        return 'Rp ' . number_format($this->remaining_amount, 0, ',', '.');
    }

    public function getInvoiceUrlAttribute()
    {
        return route('invoice.show', $this->invoice_number);
    }

    public function getAttachmentUrlAttribute()
    {
        if (!$this->attachment_path) {
            return null;
        }

        if (str_starts_with($this->attachment_path, 'http://') || str_starts_with($this->attachment_path, 'https://')) {
            return $this->attachment_path;
        }

        return asset($this->attachment_path);
    }

    public function getFormattedAttachmentSizeAttribute()
    {
        if (!$this->attachment_size) {
            return null;
        }

        $bytes = (int) $this->attachment_size;
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }

        return $bytes . ' B';
    }
}
