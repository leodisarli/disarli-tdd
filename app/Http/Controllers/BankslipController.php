<?php

namespace App\Http\Controllers;

use App\Repositories\BankslipRepository;
use App\Repositories\TransactionRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BankslipController extends Controller
{
    private $bankslipRepository;

    public function __construct(
        BankslipRepository $bankslipRepository,
        TransactionRepository $transactionRepository
    ) {
        $this->bankslipRepository = $bankslipRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function index()
    {
        return $this->bankslipRepository->listAll();
    }

    public function show($id)
    {
        $bankslip = $this->bankslipRepository->get($id);
        if (!$bankslip) {
            return new Response('Bankslip not found', 404);
        }
        return $bankslip;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'account_id' => 'int|required|min:1|exists:account,id',
            'due_date' => 'required|date|after:today',
            'value' => 'numeric|required|min:0.1|',
        ]);
        $existsBankslip = $this->bankslipRepository->exists($request->all());
        if (!empty($existsBankslip)) {
            return new Response('Duplicate bankslip', 403);
        }
        $bankslip = $this->bankslipRepository->store($request->all());
        return $bankslip;
    }

    public function pay(Request $request)
    {
        $this->validate($request, [
            'barcode' => 'required|max:26|min:26',
            'amount' => 'numeric|required|min:0.1|',
        ]);

        $amount = number_format($request->all()['amount'], 2, '.', '');
        $barcode = $request->all()['barcode'];

        $data = $this->bankslipRepository->decodeBarcode($barcode);

        $existsBankslip = $this->bankslipRepository->exists($data);
        if (empty($existsBankslip)) {
            return new Response('Bankslip not found', 404);
        }

        if ($amount !== $data['value'] || $amount !== $existsBankslip[0]['value']) {
            return new Response('Incorrect value', 403);
        }

        if (!empty($existsBankslip[0]['transaction_id'])) {
            return new Response('Bankslip already paid', 403);
        }

        $transaction = $this->transactionRepository->newTransaction([
            'source_account_id' => null,
            'target_account_id' => $data['account_id'],
            'type' => 'payment',
            'amount' => $data['value'],
        ]);

        $bankslip = $this->bankslipRepository->get($existsBankslip[0]['id']);
        $this->bankslipRepository->pay($transaction->id, $bankslip);
        
        return [
            'Bankslip successfuly paid',
        ];
    }
}
