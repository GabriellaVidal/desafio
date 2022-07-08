<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * $fillable = variavel que mapeia as colunas da table que a model faz referencia
     */
    protected $fillable = [
        'value',
        'payer_id',
        'payee_id',
        'concluded',
    ];

    /**
     * validacoes
     */
    public $rules = [
        'payee' => 'required',
        'value' => 'required',
        'payer_id' => 'required',
        'payee_id' => 'integer|required_if:payee,!=,nullable',
    ];
    public $messages = [
        'payee.required' => 'Selecione um beneficiário!',
        'value.required' => 'Informe o valor do pix!',
        'payer_id.required' => 'Usuário pagador não encontrado',
        'payee_id.required_if' => 'Usuário beneficiário não encontrado',
        'payee_id.integer' => 'Usuário beneficiário não encontrado',
    ];

    /**
     * payer()
     * mapeamento de relacionamento que define o pagador da transacao
     */
    public function payer(){
        return $this->belongsTo(User::class, 'payer_id');
    }

    /**
     * payee()
     * mapeamento de relacionamento que define o beneficiario da transacao
     */
    public function payee(){
        return $this->belongsTo(User::class, 'payee_id');
    }
}
