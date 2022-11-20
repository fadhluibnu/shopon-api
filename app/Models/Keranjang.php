<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = [
        'user',
        'product'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_produk');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
