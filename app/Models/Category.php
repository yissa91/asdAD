<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class category extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'name',
        'description',
    ];

    function ads(){
        return $this->hasMany(Ad::class);
    }
}
