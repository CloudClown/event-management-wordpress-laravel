<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'date',
        'time',
        'location',
        'category',
        'primary_image',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime',
    ];

    public function images()
    {
        return $this->hasMany(EventImage::class);
    }
}