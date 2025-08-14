<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // Tambahkan ini

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'package_id',
        'registration_date',
        'status',
        'notes'
    ];

    protected $dates = ['registration_date'];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // Accessor untuk format tanggal
    public function getFormattedRegistrationDateAttribute()
    {
        if ($this->registration_date) {
            return Carbon::parse($this->registration_date)->format('d/m/Y');
        }
        return null; // atau format lain yang sesuai
    }

    // Accessor untuk status badge
    public function getStatusBadgeAttribute()
    {
        $statuses = [
            'active' => 'success',
            'suspended' => 'warning',
            'terminated' => 'danger'
        ];

        return '<span class="badge badge-'.$statuses[$this->status].'">'.ucfirst($this->status).'</span>';
    }
}