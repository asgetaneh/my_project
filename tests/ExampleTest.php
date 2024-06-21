<?php

use PHPUnit\Framework\TestCase;
use App\CommissionCalculator;
use App\Transaction;

class ExampleTest extends TestCase
{
    public function testExample()
    {
        $transactions = [
            ['2014-12-31', '4', 'private', 'withdraw', 1200.00, 'EUR'],
            ['2015-01-01', '4', 'private', 'withdraw', 1000.00, 'EUR'],
            ['2016-01-05', '4', 'private', 'withdraw', 1000.00, 'EUR'],
            ['2016-01-05', '1', 'private', 'deposit', 200.00, 'EUR'],
            ['2016-01-06', '2', 'business', 'withdraw', 300.00, 'EUR'],
            ['2016-01-06', '1', 'private', 'withdraw', 30000, 'JPY'],
            ['2016-01-07', '1', 'private', 'withdraw', 1000.00, 'EUR'],
            ['2016-01-10', '1', 'private', 'withdraw', 100.00, 'USD'],
            ['2016-01-10', '2', 'business', 'deposit', 10000.00, 'EUR'],
            ['2016-01-10', '3', 'private', 'withdraw', 1000.00, 'EUR']
        ];

        $calculator = new CommissionCalculator($transactions);
        $results = $calculator->process();

        $expectedResults = [
            '3.60', '0.00', '3.00', '0.06', '1.50', '0.00', '0.30', '0.30', '3.00', '0.00'
        ];

        foreach ($results as $index => $result) {
            $this->assertEquals($expectedResults[$index], $result['commission']);
        }
    }
}
