<?php

namespace App\Repositories;

use App\Models\Client;

class ClientRepository
{
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function listAll()
    {
        return $this->client->all();
    }

    public function get($id)
    {
        return $this->client->find($id);
    }

    public function store(Client $client)
    {
        $client->save();
        return $client;
    }
}