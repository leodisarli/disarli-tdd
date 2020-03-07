<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Account;

class Client extends Model
{
    public $timestamps = false;

    protected $table = 'client';
    protected $fillable = ['name', 'cpf'];
    protected $hidden = ['password'];

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
