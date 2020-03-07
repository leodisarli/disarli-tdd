<?php

namespace App\Http\Controllers;

use App\Repositories\AccountRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    private $accountRepository;

    public function __construct(
        AccountRepository $accountRepository,
        TransactionRepository $transactionRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function index()
    {
        return $this->accountRepository->listAll();
    }

    public function balance($id)
    {
        $account = $this->accountRepository->get($id);
        if (!$account) {
            return new Response('Account not found', 404);
        }
        return $account;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'client_id' => 'int|required|min:1|unique:account,client_id|exists:client,id',
        ]);
        $account = $this->accountRepository->store($request->all());
        return $account;
    }

    public function activate($id)
    {
        $account = $this->accountRepository->get($id);
        if (!$account) {
            return new Response('Account not found', 404);
        }
        $this->accountRepository->activate($account);
        return [
            'Activated Successfuly',
        ];
    }

    public function deactivate($id)
    {
        $account = $this->accountRepository->get($id);
        if (!$account) {
            return new Response('Account not found', 404);
        }
        $this->accountRepository->deactivate($account);
        return [
            'Deactivated Successfuly',
        ];
    }

    public function deposit($id, Request $request)
    {
        $account = $this->accountRepository->get($id);
        if (!$account) {
            return new Response('Account not found', 404);
        }
        $this->validate($request, [
            'amount' => 'numeric|required|min:0.1',
        ]);

        $amount = $request->all()['amount'];
        $account = $this->accountRepository->deposit($account, $amount);
        $this->transactionRepository->newTransaction([
            'source_account_id' => null,
            'target_account_id' => $account->id,
            'type' => 'deposit',
            'amount' => $amount,
        ]);
        return $account;
    }

    public function withdraw($id, Request $request)
    {
        $account = $this->accountRepository->get($id);
        if (!$account) {
            return new Response('Account not found', 404);
        }

        $this->validate($request, [
            'amount' => 'numeric|required|min:0.1|',
        ]);

        $amount = $request->all()['amount'];
        if ($amount > $account->balance) {
            return new Response('Insufficient funds', 403);
        }

        $account = $this->accountRepository->withdraw($account, $amount);
        $this->transactionRepository->newTransaction([
            'source_account_id' => null,
            'target_account_id' => $account->id,
            'type' => 'withdraw',
            'amount' => $amount,
        ]);
        return $account;
    }

    public function extract($id, Request $request)
    {
        $this->validate($request, [
            'month' => 'required|min:7|max:7',
        ]);
        $month = $request->all()['month'];
        return $this->transactionRepository->extract($id, $month);
    }

    public function transfer($id, Request $request)
    {
        $sourceAccount = $this->accountRepository->get($id);
        if (!$sourceAccount) {
            return new Response('Source account not found', 404);
        }

        $this->validate($request, [
            'account_id' => 'int|required|min:1|exists:account,id',
            'amount' => 'numeric|required|min:0.1|',
        ]);

        $amount = $request->all()['amount'];
        $targetAccountId = $request->all()['account_id'];

        if ($id == $targetAccountId) {
            return new Response('Same accounts', 403);
        }

        if ($amount > $sourceAccount->balance) {
            return new Response('Insufficient funds', 403);
        }

        $targetAccount = $this->accountRepository->get($targetAccountId);

        $sourceAccount = $this->accountRepository->withdraw($sourceAccount, $amount);
        $this->accountRepository->deposit($targetAccount, $amount);

        $this->transactionRepository->newTransaction([
            'source_account_id' => $id,
            'target_account_id' => $targetAccountId,
            'type' => 'transfer',
            'amount' => $amount,
        ]);
        return $sourceAccount;
    }
}
