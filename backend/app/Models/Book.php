<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['seller_id','category_id','title','author','price','stock','description','cover_img'];
    public function seller() { return $this->belongsTo(Seller::class); }
    public function category() { return $this->belongsTo(Category::class); }
}
