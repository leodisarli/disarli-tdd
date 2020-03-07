<?php

namespace App\Services;

use Illuminate\Contracts\Hashing\Hasher;

use App\Repositories\ClientRepository;
use App\Models\Client;

class ClientService
{
    private $repository;
    private $hasher;

    public function __construct(
        ClientRepository $repository,
        Hasher $hasher
    ) {
        $this->repository = $repository;
        $this->hasher = $hasher;
    }

    public function store($data)
    {
        $client = new Client;
        $client->fill($data);

        $client->password = $this->hasher->make($data['password']);
        
        $this->repository->store($client);
        return $client;
    }
}