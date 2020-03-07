<?php

namespace App\Repositories;

use PHPUnit\Framework\TestCase;
use \Mockery as M;

use App\Models\Account;

class AccountRepositoryTest extends TestCase
{
    public function testShoultCreateTheRepository()
    {
        $accountModelMock = M::mock(Account::class);

        $repository = new AccountRepository($accountModelMock);

        $this->assertInstanceOf(AccountRepository::class, $repository);
    }

    public function testShouldListAllAccounts()
    {
        $accountModelMock = M::mock(Account::class)
            ->shouldReceive('all')
            ->once()
            ->withNoArgs()
            ->andReturn([
                new Account,
                new Account
            ])
            ->getMock();

        $repository = new AccountRepository($accountModelMock);

        $list = $repository->listAll();

        $this->assertEquals(2, count($list));
    }

    public function testShouldFindAAccountById()
    {
        $accountModelMock = M::mock(Account::class)
            ->shouldReceive('find')
            ->once()
            ->with(123)
            ->andReturnUsing(function($args) {
                $account = new Account;
                $account->id = $args;
                $account->client_id = 1;
                $account->balance = 20;

                return $account;
            })
            ->getMock();

        $repository = new AccountRepository($accountModelMock);

        $account = $repository->get(123);

        $this->assertEquals(20, $account->balance);
        $this->assertEquals(1, $account->client_id);
        $this->assertEquals(123, $account->id);
    }

    public function testShouldReturnNullIfThereIsNoAccountWithId()
    {
        $accountModelMock = M::mock(Account::class)
            ->shouldReceive('find')
            ->once()
            ->with(123)
            ->andReturn(null)
            ->getMock();

        $repository = new AccountRepository($accountModelMock);

        $account = $repository->get(123);

        $this->assertNull($account);
    }

    public function tearDown()
    {
        M::close();
    }
}
