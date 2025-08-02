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
        'phone',
        'email',
        'address',
        'package_id',
        'status',
        'registration_date',
        'installation_date'
    ];

    protected $casts = [
        'registration_date' => 'date',
        'installation_date' => 'date'
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'active' => '<span class="badge bg-success">Aktif</span>',
            'inactive' => '<span class="badge bg-secondary">Tidak Aktif</span>',
            'suspended' => '<span class="badge bg-warning">Ditangguhkan</span>'
        ];

        return $badges[$this->status] ?? $badges['inactive'];
    }

    public function getLatestInvoiceAttribute()
    {
        return $this->invoices()->latest()->first();
    }
}