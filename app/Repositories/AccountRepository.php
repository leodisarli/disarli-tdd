<?php

namespace App\Repositories;

use App\Models\Account;

class AccountRepository
{
    private $account;

    public function __construct(Account $account)
    {
        $this->account = $account;
    }

    public function listAll()
    {
        return $this->account->all();
    }

    public function get($id)
    {
        return $this->account->find($id);
    }

    public function store($data)
    {
        $account = new Account;
        $account->fill($data);
        $account->save();
        return $account;
    }

    public function activate(Account $account)
    {
        $account->active = 1;
        $account->save();
    }

    public function deactivate(Account $account)
    {
        $account->active = 0;
        $account->save();
    }

    public function deposit(Account $account, $amount)
    {
        $currentBalance = $account->balance;
        $account->balance = $currentBalance + $amount;
        $account->save();
        return $account;
    }

    public function withdraw(Account $account, $amount)
    {
        $currentBalance = $account->balance;
        $account->balance = $currentBalance - $amount;
        $account->save();
        return $account;
    }

    public function listByClientId($clientId)
    {
        return $this->account->where('client_id', $clientId)->first();
    }
}