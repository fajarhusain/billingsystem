<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'period',
        'amount',
        'due_date',
        'status',
        'payment_date',
        'payment_method',
        'notes'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'payment_date' => 'date'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'paid' => '<span class="badge bg-success">Lunas</span>',
            'unpaid' => '<span class="badge bg-warning">Belum Bayar</span>',
            'overdue' => '<span class="badge bg-danger">Terlambat</span>',
            'cancelled' => '<span class="badge bg-secondary">Dibatalkan</span>'
        ];

        return $badges[$this->status] ?? $badges['unpaid'];
    }

    public function getIsOverdueAttribute()
    {
        return $this->status === 'unpaid' && $this->due_date < Carbon::now();
    }

    public static function generateInvoiceNumber($customerId, $period)
    {
        $year = substr($period, -4);
        $month = substr($period, 0, 2);
        return "INV-{$year}{$month}-{$customerId}";
    }
}