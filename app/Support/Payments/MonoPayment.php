<?php

namespace App\Support\Payments;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Docs https://api.monobank.ua/docs/acquiring.html
 */
final class MonoPayment
{
    protected $apiUrl = "https://api.monobank.ua/api/merchant/invoice/create";
    protected string $paymentToken = '';

    /**
     * Чи увімкнена фіскалізація.
     * @var bool
     */
    protected bool $fiscal = false;

    protected string|null $xCms = '';

    public function __construct(array $options = [])
    {
        $group = Arr::get($options, 'group') ?: \Domain::getId();

        $this->paymentToken = Arr::get($options, 'payment_token') ?? \Variable::getArray('payments.monobank.payment_token', '', $group);
        $this->fiscal = Arr::get($options, 'fiscal') ?? \Variable::getArray('payments.monobank.fiscal', false, $group);
        $this->xCms = Arr::get($options, 'x_cms') ?? \Variable::getArray('payments.monobank.x_cms', '', $group);
    }

    public function initApi()
    {
        if (empty($this->paymentToken)) {
            throw new \Exception('Не задано Токен платіжної системи!');
        }
    }

    public function pay(Payment $payment)
    {
        $this->debugLog(__METHOD__, ['payment' => $payment]);

        $order = $payment->model;

        if (!($order instanceof Order)) {
            Log::info(__METHOD__ . ' Fail');
            return false;
        }

        $this->initApi();

        $amount = intval($payment->amount * 100);
        $ccy = match ($payment->currency_code) {    // https://index.minfin.com.ua/reference/currency/code/
            'USD' => 840,
            'EUR' => 978,
            default => 980, // UAH
        };

        $data = [
            'amount' => $amount,
            'ccy' => $ccy,
            'merchantPaymInfo' => [
                'reference' => $order->number,
                'destination' => "Замовлення #{$order->number}",
            ],
            'webHookUrl' => route('webhooks.payment.notify', ['monobank']),
            'redirectUrl' => route('payment.info', ['progress', 'order_number' => $order->number]),
        ];

        if ($this->fiscal) {
            $data['merchantPaymInfo']['basketOrder'] = $this->getPurchases($order);
        }
        if ($email = $order->added['recipient']['email'] ?? null) {
            $data['merchantPaymInfo']['customerEmails'] = [$email];
        }

        $headers['X-Token'] = $this->paymentToken;
        if ($this->xCms) {
            $headers['X-Cms'] = $this->xCms;
        }

        try {
            $response = Http::withHeaders($headers)->post($this->apiUrl, $data);

            $responseData = json_decode($response, true);

            if (empty($responseData['invoiceId']) || empty($responseData['pageUrl'])) {
                Log::error(__METHOD__ . ' ' . json_encode($responseData, JSON_UNESCAPED_UNICODE));

                return false;
            }

            $payment->setAttribute('extern_id', $responseData['invoiceId']);
            $payment->save();

            return $responseData['pageUrl'];

        } catch (\Exception $exception) {
            Log::error(__METHOD__ . ' ' . $exception->getMessage());
        }

        return false;
    }

    /**
     * Обробка відповіді від платіжної, після ручної оплати.
     *
     * @param $gateway
     * @param array $data
     */
    public function processNotify(array $data)
    {
        $this->debugLog(__METHOD__, $data);

        if ($externId = Arr::get($data, 'invoiceId')) {
            if ($payment = Payment::where('status', '<>', Payment::STATUS_PAID)
                ->where('extern_id', $externId)
                ->first()
            ) {
                if (Arr::get($data, 'status') === 'success') {
                    $payment->setAttribute('status', Payment::STATUS_PAID);
                    //$payment->setAttribute('paid_at', now()->subMinute());
                    $payment->setAttribute('added->notify', $data);
                    $payment->save();

                    return $payment;
                }
            } else {
                Log::warning(__METHOD__ . " | Not found payment extern_id [{$externId}]!");
            }
            return true;
        } else {
            Log::error(__METHOD__ . " | Gateway [Monobank] data is not correct!");
        }

        return false;
    }

    /**
     * @param Order $order
     * @return array
     */
    protected function getPurchases(Order $order): array
    {
        $cartPurchases = [];

        foreach ($order->purchases->load('variation.translations') as $purchase) {
            $data = [
                'name' => $purchase->getName(),
                'qty' => $purchase->quantity,
                'sum' => intVal($purchase->price * 100),
                'code' => $purchase->getSku(),

            ];

            if ($purchase->discount) {
                $data['discounts'][] = [
                    'type' => 'DISCOUNT',
                    'mode' => 'VALUE',
                    'value' => $purchase->discount,
                ];
            }

            $cartPurchases[] = $data;
        }

        return $cartPurchases;
    }

    /**
     * @param string $method
     * @param array $data
     * @return void
     */
    protected function debugLog(string $method, array $data): void
    {
        if (config('services.mono.debug')) {
            Log::debug($method, $data);
        }
    }
}
