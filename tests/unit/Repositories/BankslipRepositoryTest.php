<?php

namespace App\Repositories;

use PHPUnit\Framework\TestCase;
use \Mockery as M;

use App\Models\Bankslip;

class BankslipRepositoryTest extends TestCase
{

    public function testShoultCreateTheRepository()
    {
        $bankslipModelMock = M::mock(Bankslip::class);

        $repository = new BankslipRepository($bankslipModelMock);

        $this->assertInstanceOf(BankslipRepository::class, $repository);
    }

    public function testShouldListAllBankslips()
    {
        $bankslipModelMock = M::mock(Bankslip::class)
            ->shouldReceive('all')
            ->once()
            ->withNoArgs()
            ->andReturn([
                new Bankslip,
                new Bankslip
            ])
            ->getMock();

        $repository = new BankslipRepository($bankslipModelMock);

        $list = $repository->listAll();

        $this->assertEquals(2, count($list));
    }

    public function testShouldFindABankslipById()
    {
        $bankslipModelMock = M::mock(Bankslip::class)
            ->shouldReceive('find')
            ->once()
            ->with(123)
            ->andReturnUsing(function($args) {
                $bankslip = new Bankslip;
                $bankslip->id = $args;
                $bankslip->account_id = 1;
                $bankslip->transaction_id = null;
                $bankslip->due_date = '2018-12-31 00:00:00';
                $bankslip->value = 120;

                return $bankslip;
            })
            ->getMock();

        $repository = new BankslipRepository($bankslipModelMock);

        $bankslip = $repository->get(123);

        $this->assertEquals(120, $bankslip->value);
        $this->assertEquals('2018-12-31 00:00:00', $bankslip->due_date);
        $this->assertEquals(null, $bankslip->transaction_id);
        $this->assertEquals(123, $bankslip->id);
    }

    public function testShouldReturnNullIfThereIsNoBankslipWithId()
    {
        $bankslipModelMock = M::mock(Bankslip::class)
            ->shouldReceive('find')
            ->once()
            ->with(123)
            ->andReturn(null)
            ->getMock();

        $repository = new BankslipRepository($bankslipModelMock);

        $bankslip = $repository->get(123);

        $this->assertNull($bankslip);
    }

    public function tearDown()
    {
        M::close();
    }

}
