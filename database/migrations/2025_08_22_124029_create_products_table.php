<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'categorie',
        'description',
        'prix',      // <-- assure-toi que c’est 'prix' et non 'price'
        'stock',
        'image_url',
        'favori'
    ];
}
