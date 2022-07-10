<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\Notification;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\ResponseJSON;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    use ResponseJSON;

    public $model;

    public function __construct(Transaction $transaction)
    {
        $this->model = $transaction;
    }

    public function pix(Request $request)
    {
        $dados = $request->all();

        if (auth()->user()) {
            $dados["payer_id"] = auth()->user()->id;
        }

        if (!empty($dados["payee"])) {
            $dados["payee_id"] = User::buscaBeneficiario($dados["payee"])->first()->id ?? null;
        }

        if (isset($this->model->rules)) {
            $validator = Validator::make($dados, $this->model->rules ?? [], $this->model->messages ?? []);
            if ($validator->fails()) {
                return $this->validateFails("Problemas com as informações enviadas.", $validator->messages()->toArray());
            }
        }

        try {
            return DB::transaction(function () use ($dados) {
                if (auth()->user()->profile_id == 1) {
                    return $this->validateFails("Ops! Logistas não podem realizar pix, apenas receber.");
                }

                if (auth()->user()->wallet->amount <= 0 || auth()->user()->wallet->amount < $dados["value"]) {
                    return $this->validateFails("Ops! Você não tem saldo na carteira para realizar a transação. ");
                }

                $result = $this->model->create($dados);

                if (!empty($result)) {
                    $efetivation = self::efetivation($result);

                    return $efetivation;
                }

            });
        } catch (\Exception $e) {
            $this->codeError(500);
            return $this->error("Error");
        }
    }

    private function efetivation($result)
    {
        $wallet_payer = $result->payer->wallet;
        $amount_payer = $wallet_payer->amount;

        $wallet_payee = $result->payee->wallet;
        $amount_payee = $wallet_payee->amount;

        try {
            return DB::transaction(function () use ($result, $wallet_payer, $wallet_payee) {
                $response_autorrized = self::authorizing();

                if(!isset($response_autorrized->message) || $response_autorrized->message !== "Autorizado" || empty($response_autorrized)) {
                    $this->codeError(401);
                    return $this->error("Error autorizacao");
                }

                $result->payer->wallet()->update(["amount" => $wallet_payer->amount - $result->value]);//diminuindo
                $result->payee->wallet()->update(["amount" => $wallet_payee->amount + $result->value]);//aumentando

                $result->update(['concluded' => 1]);

                if ($result->concluded) {
                    Notification::dispatch();
                }

                return $this->success($result->id, "Success");
            });
        } catch (\Exception $e) {
            self::devolution($result, $wallet_payer, $amount_payer, $wallet_payee, $amount_payee);
            $this->codeError(500);
            return $this->error("Error efetivação");
        }
    }

    private function devolution($result, $wallet_payer, $amount_payer, $wallet_payee, $amount_payee)
    {
        try {
            return DB::transaction(function () use ($result, $wallet_payer, $wallet_payee, $amount_payer, $amount_payee) {
                if ($amount_payer < $result->payer->wallet->amount) {
                    $result->payer->wallet()->update(["amount" => $wallet_payer->amount + $result->value]);//voltando o pagador
                }

                if ($amount_payee > $result->payee->wallet->amount) {
                    $result->payee->wallet()->update(["amount" => $wallet_payee->amount - $result->value]);//voltando o beneficiario
                }

                $result->update(['concluded' => 0]);
            });
        } catch (\Exception $e) {
            $this->codeError(500);
            return $this->error("Error devolução");
        }
    }

    private function authorizing()
    {
        $response = Http::get('https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6');

        if($response->failed()) {
            $this->codeError(401);
            return $this->error("Error autorização");
        }

        return json_decode($response->body());
//        return json_decode("{}");

    }
}
