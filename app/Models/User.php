<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    /*
     * $fillable = variavel que mapeia as colunas da table que a model faz referencia
     */
    protected $fillable = [
        'name',
        'email',
        'cpf',
        'password',
        'profile_id',
    ];

    /*
     * profile()
     * mapeamento de relacionamento que define o tipo de usuario
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class, 'profile_id');
    }

    /*
     * wallet()
     * mapeamento de relacionamento que define a carteira
     */
    public function wallet()
    {
        return $this->hasOne(Wallet::class, 'user_id');
    }

    /*
     * user()
     * mapeamento de relacionamento que define as transacoes que este usuario foi pagador
     */
    public function transactionsPayer()
    {
        return $this->hasMany(Transaction::class, 'payer_id');
    }

    /*
     * user()
     * mapeamento de relacionamento que define as transacoes que este usuario foi beneficiario
     */
    public function transactionsPayee()
    {
        return $this->hasMany(Transaction::class, 'payee_id');
    }

    /*
     * implementacao metodos jwt
     */
    public function getJWTIdentifier()
    {
        // TODO: Implement getJWTIdentifier() method.
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        // TODO: Implement getJWTCustomClaims() method.
        return [];
    }

}
