<?php

namespace App\Support\Payments;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

final class WayForPayPayment
{
    protected $account;
    protected $secret_key;
    protected $domain_merchant;

    const API_URL = 'https://secure.wayforpay.com/pay?behavior=offline';

    public function __construct(array $options = [])
    {
        $this->account = \Variable::getArray('payments.wayforpay.account', null, 'default');
        $this->secret_key = \Variable::getArray('payments.wayforpay.secret_key', null, 'default');
        $this->domain_merchant = \Variable::getArray('payments.wayforpay.domain_merchant', null, 'default');
    }

    public function initApi(): void
    {
        if (empty($this->account) || empty($this->secret_key)) {
            throw new \Exception('Wayforpay parameters not set!');
        }
    }

    public function pay(Payment $payment)
    {
        $this->debugLog(__METHOD__, ['payment' => $payment]);

        $order = $payment->model;
        if ($order instanceof Order) {
            // Перевірка наявності платіжних налаштувань
            $this->initApi();

            $time = time();
            $amount = round($payment->amount, 2);

            $orderNumber = $order->number . ':' . Str::uuid();
            $productName = 'Замовлення ' . $order->number;

            // Генерація сигнатури
            $string = $this->account . ';' . $this->domain_merchant . ';' . $orderNumber . ';' . $time . ';' . $amount . ';' . $payment->currency_code . ';' . $productName . ';' . 1 . ';' . $amount;
            $signature =  hash_hmac("md5", $string, $this->secret_key);

            $response = Http::post(self::API_URL, [
                'merchantAccount' => $this->account,
                'merchantDomainName' => $this->domain_merchant,
                'orderReference' => $orderNumber,
                'orderDate' => $time,
                'amount' => $amount,
                'currency' => $payment->currency_code,
                'productName' => [$productName],
                'productCount' => [1],
                'productPrice' => [$amount],
                'merchantSignature' => $signature,
                'returnUrl' => route('payment.info', ['progress', 'order_number' => $order->number]),
                'serviceUrl' => route('webhooks.payment.notify', ['wayforpay']),
            ]);

            if (isset($response->json()['reason'])) {
                return 'ERROR: ' . $response->json()['reason'];
            }

            $payment->setAttribute('extern_id', $orderNumber);
            $payment->save();

            return $response->json()['url'];
        }

        Log::info(__METHOD__ . 'Fail');

        return false;
    }

    /**
     * Обробка відповіді від платіжної, після ручної оплати.
     *
     * @param $gateway
     *
     */
    public function processNotify($data)
    {
        $this->debugLog(__METHOD__, $data);

        $parceData = array_keys($data)[0];

        $attrs = json_decode(str_replace('_', '.', $parceData), true);

        if ($externId = Arr::get($attrs, 'orderReference')) {

            $payment = Payment::where('extern_id', $externId)->first();

            if ($payment && $payment->status !== Payment::STATUS_PAID && Arr::get($attrs, 'transactionStatus') === 'Approved') {

                $payment->setAttribute('status', Payment::STATUS_PAID);
                //$payment->setAttribute('paid_at', now()->subMinute());
                $payment->setAttribute('added->notify', $data);
                $payment->setAttribute('added->notify_data_decode', $attrs);
                $payment->save();

                return $payment;

            } elseif ($payment && $payment->status !== Payment::STATUS_FAILED && Arr::get($attrs, 'transactionStatus') === 'Declined') {
                $payment->setAttribute('status', Payment::STATUS_FAILED);
                $payment->setAttribute('added->notify', $data);
                $payment->setAttribute('added->notify_data_decode', $attrs);
                $payment->save();

                return $payment;
            } elseif ($payment && $payment->status === Payment::STATUS_PAID) {
                Log::info(__METHOD__ . " Payment {$externId} already payed!");

                return true;
            } elseif ($payment) {
                Log::warning(__METHOD__ . " Payment {$externId} has status {$payment->status}!");

                return true;
            } else {
                Log::warning(__METHOD__ . " Payment {$externId} not found!");

                return true;
            }
        }

        Log::error(__METHOD__ . " Field [orderReference] is empty!");

        return false;
    }

    /**
     * @param string $method
     * @param array $data
     * @return void
     */
    protected function debugLog(string $method, array $data): void
    {
        if (config('services.wayforpay.debug')) {
            Log::debug($method, $data);
        }
    }
}
