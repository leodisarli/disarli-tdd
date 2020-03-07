<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Client;

class Account extends Model
{
    public $timestamps = false;

    protected $table = 'account';
    protected $fillable = ['client_id'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
