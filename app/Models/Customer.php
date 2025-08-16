<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'dusun',
        'unique_code',
        'email',
        'phone',
        'address',
        'package_id',
        'registration_date',
        'status',
        'notes'
    ];

    protected $dates = ['registration_date'];

    // Relasi ke package
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // Relasi ke invoice
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    // ✅ Relasi ke payment
    public function payments()
{
    return $this->hasManyThrough(Payment::class, Invoice::class);
}


    // Accessor format tanggal
    public function getFormattedRegistrationDateAttribute()
    {
        return $this->registration_date
            ? Carbon::parse($this->registration_date)->format('d/m/Y')
            : null;
    }

    // Accessor badge status
    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'active' => 'success',
            'suspended' => 'warning',
            'terminated' => 'danger'
        ];

        return '<span class="badge badge-' . ($statuses[$this->status] ?? 'secondary') . '">' 
            . ucfirst($this->status) . '</span>';
    }
}