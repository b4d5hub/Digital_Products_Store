<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','name','is_active','subdomain']; // Add other fillable fields as needed

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
