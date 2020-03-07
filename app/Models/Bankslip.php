<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Account;
use App\Models\Transaction;

class Bankslip extends Model
{
    protected $table = 'bankslip';

    protected $fillable = [
        'account_id',
        'due_date',
        'value',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

}
