<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Les champs qui peuvent être remplis en masse
     */
  protected $fillable = [
    'nom',
    'categorie',
    'prix',
    'stock',
    'image_url',  // Corrigé ici
    'favori',
];


    /**
     * Cast des champs pour garantir le bon type
     */
    protected $casts = [
        'prix' => 'float',
        'stock' => 'integer',
        'favori' => 'boolean',
    ];
}
