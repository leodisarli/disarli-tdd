<?php

namespace App\Repositories;

use App\Models\Bankslip;

class BankslipRepository
{
    private $bankslip;

    public function __construct(Bankslip $bankslip)
    {
        $this->bankslip = $bankslip;
    }

    public function listAll()
    {
        return $this->bankslip->all();
    }

    public function get($id)
    {
        return $this->bankslip->find($id);
    }

    public function store($data)
    {
        $bankslip = new Bankslip;
        $bankslip->fill($data);
        $bankslip->save();
        return $bankslip;
    }

    public function pay($transactionId, Bankslip $bankslip)
    {
        $bankslip->transaction_id = $transactionId;
        $bankslip->save();
    }

    public function exists($data)
    {
        $result = [];
        $bankslipList =  $this->bankslip->where([
            'account_id' => $data['account_id'],
            'due_date' => $data['due_date'],
            'value' => $data['value'],
        ])->get();
        
        foreach ($bankslipList as $bankslipItem) {
            $result[] = $bankslipItem->toArray();
        }
        return $result;
    }

    public function decodeBarcode($barcode)
    {
        $accountId = ltrim(substr($barcode, 8, 6), '0');

        $dueDate = substr($barcode, 0, 4) . '-' . substr($barcode, 4, 2) . '-' . substr($barcode, 6, 2);

        $value = ltrim(substr($barcode, 14, 10), '0');
        $decimals = substr($barcode, 24, 2);

        $value = $value . '.' . $decimals;

        return [
            'account_id' => $accountId,
            'due_date' => $dueDate,
            'value' => $value,
        ];
    }
}