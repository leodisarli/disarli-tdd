<?php

namespace App\Repositories;

use PHPUnit\Framework\TestCase;
use \Mockery as M;

use App\Models\Transaction;

class TransactionRepositoryTest extends TestCase
{
    public function testShoultCreateTheRepository()
    {
        $transactionModelMock = M::mock(Transaction::class);

        $repository = new TransactionRepository($transactionModelMock);

        $this->assertInstanceOf(TransactionRepository::class, $repository);
    }

    public function testShouldListAllTransactions()
    {
        $transactionModelMock = M::mock(Transaction::class)
            ->shouldReceive('all')
            ->once()
            ->withNoArgs()
            ->andReturn([
                new Transaction,
                new Transaction
            ])
            ->getMock();

        $repository = new TransactionRepository($transactionModelMock);

        $list = $repository->listAll();

        $this->assertEquals(2, count($list));
    }

    public function testShouldFindTransactionById()
    {
        $transactionModelMock = M::mock(Transaction::class)
            ->shouldReceive('find')
            ->once()
            ->with(123)
            ->andReturnUsing(function($args) {
                $transaction = new Transaction;
                $transaction->id = $args;
                $transaction->source_account_id = null;
                $transaction->target_account_id = 1;
                $transaction->type = 'deposit';
                $transaction->amount = 120;

                return $transaction;
            })
            ->getMock();

        $repository = new TransactionRepository($transactionModelMock);

        $transaction = $repository->get(123);

        $this->assertEquals(null, $transaction->source_account_id);
        $this->assertEquals(1, $transaction->target_account_id);
        $this->assertEquals('deposit', $transaction->type);
        $this->assertEquals(120, $transaction->amount);
        $this->assertEquals(123, $transaction->id);
    }

    public function testShouldReturnNullIfThereIsTransactionWithId()
    {
        $transactionModelMock = M::mock(Transaction::class)
            ->shouldReceive('find')
            ->once()
            ->with(123)
            ->andReturn(null)
            ->getMock();

        $repository = new TransactionRepository($transactionModelMock);

        $transaction = $repository->get(123);

        $this->assertNull($transaction);
    }

    public function tearDown()
    {
        M::close();
    }
}
