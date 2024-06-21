<?php
namespace App;

class CommissionRule {
    private $depositRate = 0.03; // 0.03% for deposit
    private $withdrawRatePrivate = 0.3; // 0.3% for private client withdrawal
    private $withdrawRateBusiness = 0.5; // 0.5% for business client withdrawal

    private $weeklyFreeLimit = 1000.00;
    private $weeklyFreeOperationsLimit = 3;

    private $weeklyWithdrawals = [];

    private $exchangeRates = [
        'EUR' => 1,
        'USD' => 1.1497,
        'JPY' => 129.53
    ];

    public function calculate(Transaction $transaction) {
        switch ($transaction->operationType) {
            case 'deposit':
                return $this->calculateDepositCommission($transaction);
            case 'withdraw':
                return $this->calculateWithdrawCommission($transaction);
            default:
                return 0;
        }
    }

    private function calculateDepositCommission(Transaction $transaction) {
        $commission = $transaction->amount * $this->depositRate / 100;
        return $this->roundUp($commission, $transaction->currency);
    }

    private function calculateWithdrawCommission(Transaction $transaction) {
        if ($transaction->clientType === 'private') {
            return $this->calculatePrivateWithdrawCommission($transaction);
        } else {
            return $this->calculateBusinessWithdrawCommission($transaction);
        }
    }

    private function calculatePrivateWithdrawCommission(Transaction $transaction) {
        $yearWeek = $this->getYearWeek($transaction->date);
        if (!isset($this->weeklyWithdrawals[$transaction->clientId])) {
            $this->weeklyWithdrawals[$transaction->clientId] = [];
        }

        if (!isset($this->weeklyWithdrawals[$transaction->clientId][$yearWeek])) {
            $this->weeklyWithdrawals[$transaction->clientId][$yearWeek] = [
                'amount' => 0,
                'count' => 0
            ];
        }

        $weekData = &$this->weeklyWithdrawals[$transaction->clientId][$yearWeek];
        if ($weekData['count'] < $this->weeklyFreeOperationsLimit && $weekData['amount'] < $this->weeklyFreeLimit) {
            $freeAmountLeft = $this->weeklyFreeLimit - $weekData['amount'];
            $commissionableAmount = max(0, $transaction->amount - $freeAmountLeft);

            $weekData['amount'] += $transaction->amount;
            $weekData['count']++;

            $commission = $commissionableAmount * $this->withdrawRatePrivate / 100;
        } else {
            $commission = $transaction->amount * $this->withdrawRatePrivate / 100;
        }

        return $this->roundUp($commission, $transaction->currency);
    }

    private function calculateBusinessWithdrawCommission(Transaction $transaction) {
        $commission = $transaction->amount * $this->withdrawRateBusiness / 100;
        return $this->roundUp($commission, $transaction->currency);
    }

    private function getYearWeek($date) {
        $datetime = new \DateTime($date);
        return $datetime->format("oW");
    }

    private function roundUp($amount, $currency) {
        $decimalPlaces = $this->getCurrencyDecimalPlaces($currency);
        return ceil($amount * pow(10, $decimalPlaces)) / pow(10, $decimalPlaces);
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
