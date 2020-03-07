<?php

namespace App\Http\Controllers;

use App\Repositories\ClientRepository;
use App\Repositories\AccountRepository;
use App\Services\ClientService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClientController extends Controller
{
    private $clientRepository;
    private $clientService;

    public function __construct(
        AccountRepository $accountRepository,
        ClientRepository $clientRepository,
        ClientService $clientService
    ) {
        $this->accountRepository = $accountRepository;
        $this->clientRepository = $clientRepository;
        $this->clientService = $clientService;
    }

    public function index()
    {
        return $this->clientRepository->listAll();
    }

    public function show($id)
    {
        $client = $this->clientRepository->get($id);
        if (!$client) {
            return new Response('Client not found', 404);
        }
        return $client;
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:60',
            'cpf' => 'required|min:11|max:11|unique:client,cpf',
            'password' => 'required|min:6'
        ]);
        $client = $this->clientService->store($request->all());
        return $client;
    }

    public function listAccounts($id)
    {
        return $this->accountRepository->listByClientId($id);
    }
}
