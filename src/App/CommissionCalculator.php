<?php
namespace App;

class CommissionCalculator {
    private $transactions;
    private $commissionRule;

    public function __construct(array $transactions) {
        $this->transactions = $transactions;
        $this->commissionRule = new CommissionRule();
    }

    public function process() {
        $results = [];
        foreach ($this->transactions as $transactionData) {
            $transaction = new Transaction($transactionData);
            $commission = $this->commissionRule->calculate($transaction);
            $results[] = [
                'clientId' => $transaction->clientId,
                'commission' => number_format($commission, $this->getCurrencyDecimalPlaces($transaction->currency), '.', '')
            ];
        }
        return $results;
    }

    private function getCurrencyDecimalPlaces($currency) {
        $currencyDecimals = [
            'EUR' => 2,
            'USD' => 2,
            'JPY' => 0
        ];
        return $currencyDecimals[$currency] ?? 2;
    }
}
