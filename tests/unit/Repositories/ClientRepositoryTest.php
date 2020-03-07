<?php

namespace App\Repositories;

use PHPUnit\Framework\TestCase;
use \Mockery as M;

use App\Models\Client;

class ClientRepositoryTest extends TestCase
{
    public function testShoultCreateTheRepository()
    {
        $clientModelMock = M::mock(Client::class);

        $repository = new ClientRepository($clientModelMock);

        $this->assertInstanceOf(ClientRepository::class, $repository);
    }

    public function testShouldListAllClients()
    {
        $clientModelMock = M::mock(Client::class)
            ->shouldReceive('all')
            ->once()
            ->withNoArgs()
            ->andReturn([
                new Client,
                new Client
            ])
            ->getMock();

        $repository = new ClientRepository($clientModelMock);

        $list = $repository->listAll();

        $this->assertEquals(2, count($list));
    }

    public function testShouldFindAClientById()
    {
        $clientModelMock = M::mock(Client::class)
            ->shouldReceive('find')
            ->once()
            ->with(123)
            ->andReturnUsing(function($args) {
                $client = new Client;
                $client->id = $args;
                $client->name = 'fulano';
                $client->cpf = '12345678909';

                return $client;
            })
            ->getMock();

        $repository = new ClientRepository($clientModelMock);

        $client = $repository->get(123);

        $this->assertEquals("fulano", $client->name);
        $this->assertEquals("12345678909", $client->cpf);
        $this->assertEquals(123, $client->id);
    }

    public function testShouldReturnNullIfThereIsNoClientWithId()
    {
        $clientModelMock = M::mock(Client::class)
            ->shouldReceive('find')
            ->once()
            ->with(123)
            ->andReturn(null)
            ->getMock();

        $repository = new ClientRepository($clientModelMock);

        $client = $repository->get(123);

        $this->assertNull($client);
    }

    public function testShouldSaveAClient()
    {
        $clientModelMock = M::mock(Client::class)
            ->shouldReceive('save')
            ->once()
            ->withNoArgs()
            ->andReturnSelf()
            ->getMock();

        $repository = new ClientRepository(new Client);

        $client = $repository->store($clientModelMock);

        $this->assertSame($clientModelMock, $client);
    }

    public function tearDown()
    {
        M::close();
    }

}
