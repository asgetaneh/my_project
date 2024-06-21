<?php
namespace App;

class Transaction {
    public $date;
    public $clientId;
    public $clientType;
    public $operationType;
    public $amount;
    public $currency;

    public function __construct($data) {
        list($this->date, $this->clientId, $this->clientType, $this->operationType, $this->amount, $this->currency) = $data;
    }
}
