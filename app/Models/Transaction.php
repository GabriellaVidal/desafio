<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /*
     * $fillable = variavel que mapeia as colunas da table que a model faz referencia
     */
    protected $fillable = [
        'value',
        'payer_id',
        'payee_id',
    ];

    /*
     * payer()
     * mapeamento de relacionamento que define o pagador da transacao
     */
    public function payer(){
        return $this->belongsTo(User::class, 'payer_id');
    }

    /*
     * payee()
     * mapeamento de relacionamento que define o beneficiario da transacao
     */
    public function payee(){
        return $this->belongsTo(User::class, 'payee_id');
    }
}
