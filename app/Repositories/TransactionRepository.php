<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository
{
    private $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function listAll()
    {
        return $this->transaction->all();
    }

    public function get($id)
    {
        return $this->transaction->find($id);
    }

    public function newTransaction($data)
    {
        $transaction = new Transaction;
        $transaction->fill($data);
        $transaction->save();
        return $transaction;
    }

    public function extract($accountId, $month)
    {
        $firstDay = $month . '-01';
        $lastDay = date("Y-m-t", strtotime($firstDay));
        $result = [];
        $transactionList =  $this->transaction->where(
            function ($query) use ($accountId) {
                $query->where('source_account_id', '=', $accountId)
                    ->orWhere('target_account_id', '=', $accountId);
            }
        )->where('created_at', '>=', $firstDay)
            ->where('created_at', '<=', $lastDay)
            ->get();
        foreach ($transactionList as $transactionItem) {
            $result[] = $transactionItem->toArray();
        }
        return $result;
    }
}