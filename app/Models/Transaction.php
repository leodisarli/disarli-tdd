<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Account;

class Transaction extends Model
{
    protected $table = 'transaction';

    protected $fillable = [
        'source_account_id',
        'target_account_id',
        'type',
        'amount',
    ];

    public function sourceAccount()
    {
        return $this->belongsTo(Account::class, 'source_account_id');
    }

    public function targetAccount()
    {
        return $this->belongsTo(Account::class, 'target_account_id');
    }
}
