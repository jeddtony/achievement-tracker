<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Badge extends Model
{
    use HasFactory;
    protected $fillable = [
        'slug',
        'name',
        'description',
        'no_of_achievements',
        'next_badge_id'
    ];
}
