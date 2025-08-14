<?php

namespace App\Models;

// app/Models/Package.php


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'speed_mbps',
        'quota',
        'price',
        'status',
        'description'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'speed_mbps' => 'integer'
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function getFormattedPriceAttribute()
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }
}