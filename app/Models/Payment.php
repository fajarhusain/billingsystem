<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Payment extends Model
{
     use HasFactory;

    protected $fillable = [
    'invoice_id',
    'customer_id',
    'amount',
    'payment_date',
    'payment_method',
    'reference_number',
    'notes'
];


   protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date'
    ];

    

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // Relasi ke Invoice
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Relasi ke Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    // Format amount ke Rupiah
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    // Format tanggal pembayaran
    public function getFormattedPaymentDateAttribute()
    {
        return $this->payment_date 
            ? Carbon::parse($this->payment_date)->format('d/m/Y') 
            : null;
    }

    // Badge untuk metode pembayaran
    public function getPaymentMethodBadgeAttribute()
    {
        $badges = [
            'cash'   => 'success',
            'transfer' => 'primary',
            'qris'   => 'info',
            'other'  => 'secondary',
        ];

        $label = ucfirst($this->payment_method ?? 'other');
        $color = $badges[$this->payment_method] ?? 'secondary';

        return '<span class="badge badge-'.$color.'">'.$label.'</span>';
    }
}