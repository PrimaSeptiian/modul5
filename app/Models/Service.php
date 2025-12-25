<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Service extends Model
{
    use HasFactory;

    // Izinkan input massal untuk kolom-kolom ini
    protected $fillable = [
        'uuid', 
        'name', 
        'price', 
        'description'
    ];

    // Event untuk otomatis mengisi UUID saat data dibuat
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }
}