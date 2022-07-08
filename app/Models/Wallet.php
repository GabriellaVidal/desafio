<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    /**
     * $fillable = variavel que mapeia as colunas da table que a model faz referencia
     */
    protected $fillable = [
        'amout',
        'user_id',
    ];

    /**
     * user()
     * mapeamento de relacionamento que define o dono da carteira
     */
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
