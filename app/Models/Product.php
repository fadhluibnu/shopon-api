<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    protected $with = [
        'user'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function keranjangs()
    {
        return $this->hasMany(Keranjang::class, 'id_produk');
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
