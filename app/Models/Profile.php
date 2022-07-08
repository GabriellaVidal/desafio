<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    /**
     * $fillable = variavel que mapeia as colunas da table que a model faz referencia
     */
    protected $fillable = [
        'profile',
    ];
}
