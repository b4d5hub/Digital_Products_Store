<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'image',
        'description',
        'digital_item',
        'quantity',
        'price',
        'is_visible',
        'is_featured',
        'type',
        'published_at'
    ];

    // public function brand(){
    //     return $this->belongsTo(Brand::class);
    // }
    
    public function categories(){
        return $this->belongsToMany(Category::class)->withTimestamps();
    }
}
