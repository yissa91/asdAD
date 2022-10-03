<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'price',
        'specifications',
        'available',
        'user_id',
        'category_id',
        'view_number'
    ];

    public function user()
    {
        return $this->belongsTo(User::class); // Omit the second parameter if you're following convention
    }

    public function category()
    {
        return $this->belongsTo(category::class);
    }

    public function images()
    {
        return $this->morphMany(ImageItem::class, 'owner');
    }

}
