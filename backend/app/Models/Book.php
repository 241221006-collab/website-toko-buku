<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'seller_id', 'category_id', 'title', 'author',
        'price', 'stock', 'description', 'cover_img',
    ];

    protected $appends = ['cover_url'];

    public function getCoverUrlAttribute()
    {
        return $this->cover_img ? asset('storage/' . $this->cover_img) : null;
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}