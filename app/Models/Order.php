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
        'total_amount',
        'dp_amount',
        'remaining_amount',
        'payment_scheme',
        'payment_status',
        'project_status',
        'pakasir_trx_id',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'addons' => 'array',
        'total_amount' => 'integer',
        'dp_amount' => 'integer',
        'remaining_amount' => 'integer',
    ];

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
}
