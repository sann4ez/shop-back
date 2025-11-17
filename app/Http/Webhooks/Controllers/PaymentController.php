<?php

namespace App\Http\Webhooks\Controllers;

use App\Support\Payments\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController
{
    /**
     * Отримання callback від платіжного сервісу.
     * Set: https://site.com/webhooks/payment/notify/{gateway}
     *
     * @param Request $request
     * @param $gateway
     * @param PaymentManager $paymentManager
     * @return string|void
     */
    public function notify(Request $request, $gateway, PaymentManager $paymentManager)
    {
        Log::info(__METHOD__, ['gateway' => $gateway, $request->all()]);

        if ($paymentManager->processNotify($gateway, $request->all())) {
            return 'ok';
        }
    }
}
