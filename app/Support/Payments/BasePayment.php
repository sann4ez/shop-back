<?php

namespace App\Support\Payments;

use App\Models\Extern\Payment;

abstract class BasePayment
{
    abstract public function initApi();

    abstract public function pay(Payment $payment);

    abstract public function processNotify(array $data);

    abstract protected function debugLog(string $method, array $data): void;
}
