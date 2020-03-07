<?php

namespace App\Services;

use PHPUnit\Framework\TestCase;
use \Mockery as M;

use App\Models\Client;
use App\Repositories\ClientRepository;
use Illuminate\Contracts\Hashing\Hasher;

class ClientServiceTest extends TestCase
{

    public function testServiceCanBeInstantiated()
    {
        $repositorySpy = M::spy(ClientRepository::class);
        $hasherSpy = M::spy(Hasher::class);

        $service = new ClientService($repositorySpy, $hasherSpy);

        $this->assertInstanceOf(ClientService::class, $service);
    }

    public function testServiceShouldStoreANewClient()
    {
        $hasherMock = M::mock(Hasher::class)
            ->shouldReceive('make')
            ->once()
            ->with('123456')
            ->andReturn('encrypted_password')
            ->getMock();

        $repositoryMock = M::mock(ClientRepository::class)
            ->shouldReceive('store')
            ->once()
            ->withArgs(function($obj) {
                return $obj instanceof Client;
            })
            ->andReturnUsing(function($obj) {
                $obj->id = 10;
                return $obj;
            })
            ->getMock();

        $service = new ClientService($repositoryMock, $hasherMock);

        $data = [
            "name" => "carlos",
            "cpf" => "11111111111",
            "password" => "123456"
        ];

        $client = $service->store($data);

        $this->assertInstanceOf(Client::class, $client);
        $this->assertEquals("encrypted_password", $client->password);
        $this->assertEquals(10, $client->id);
        $this->assertEquals($data["cpf"], $client->cpf);
        $this->assertEquals($data["name"], $client->name);
    }

    public function tearDown()
    {
        M::close();
    }
}
