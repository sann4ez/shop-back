<?php

namespace App\Support\Payments;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

final class PaymentManager
{
    /**
     * Зробити оплату.
     *
     * @param string $gateway
     * @param Order $order
     * @param array $options
     * @return array
     */
    public function doPay(string $gateway, Order $order, array $options = []): array
    {
        $result = false;

        if ($gateway !== 'no') {

            /** @var Payment $payment */
            $payment = $order->payments()->updateOrCreate([
                'status' => Payment::STATUS_PENDING,
            ], [
                'gateway' => $gateway,
                'amount' => Arr::get($options, 'amount') ?? $order->totalSum(),
                'currency_code' => $order->currency_code,
                'locale_code' => app()->getLocale(),
                'user_id' => $order->user_id ?: \Auth::id(),
            ]);

            $result = $this->doOnlinePay($payment, $options);
        }

        // замовлення без онлайн оплати (при отриманні, по реквізитам, і т.д.)
        // або помилка сервісу онлайн платежів
        if ($result === false) {
            $result = [
                'order_id' => $order->id,
                'status' => 'success',
                'res' => [],
                'message' => trans('alerts.cart.success'),
            ];

            $order->doOrder();
        }

        return $result;
    }

    /**
     * Зробити онлайн оплату.
     *
     * @param Payment $payment
     * @param array $options
     * @return array|false
     */
    public function doOnlinePay(Payment $payment, array $options = []): array|bool
    {
        /** @var Order $order */
        $order = $payment->model;
        $expiresAt = now()->addMinutes(60);

        $result = [
            'order_id' => $order->id,
            'status' => 'success',
            'res' => [],
            'message' => 'Success',
        ];

        if ($url = $payment->getUrl()) {
            $result['res']['url'] = $url;

            return $result;
        }

        try {
            switch ($payment->gateway) {
                case Payment::GATEWAY_WAYFORPAY:
                    $api = new WayForPayPayment();

                    $result['message'] = 'WayForPay payment...';
                    $result['res']['url'] = $api->pay($payment);
                    break;

                case Payment::GATEWAY_MONOBANK:
                    $api = new MonoPayment();

                    $result['message'] = 'Mono payment...';
                    $result['res']['url'] = $api->pay($payment);
                    break;
                default:
                    return false;
            }
        } catch (\Exception $e) {
            \Log::error(__METHOD__ . ' ' . $e->getMessage());

            $result['status'] = 'error';
            $result['message'] = $e->getMessage();
        }

        if ($url = Arr::get($result, 'res.url')) {
            $payment->setAttribute('payment_url', $url);
            $payment->setAttribute('payment_url_expires_at', $expiresAt)->saveQuietly();
        }

        $payment->saveQuietly();

        return $result;
    }

    /**
     * Обробка callback від платіжного сервісу.
     *
     * @param string $gateway
     * @param $data
     * @return Payment|bool|null
     */
    public function processNotify(string $gateway, $data)
    {
        $payment = false;

        switch ($gateway) {
            case Payment::GATEWAY_WAYFORPAY:
                $fondy = new WayForPayPayment();
                $payment = $fondy->processNotify($data);
                break;
            case Payment::GATEWAY_MONOBANK:
                $fondy = new MonoPayment();
                $payment = $fondy->processNotify($data);
                break;
            default:
                Log::warning("Payment gateway [{$gateway}] not found!");
        }

        // !!! Уважно перевірити, щоб платіжні класи повертали true - якщо платіж є і вже підтверджено, false - якщо не ок, $payment - якщо підтверджено успішно !!!
        if ($payment instanceof Payment && $payment->model instanceof Order) {
            if ($payment->status === Payment::STATUS_PAID) {
                $payment->model->doOrder();

                if (in_array('onlinepaid', \Variable::getArray('checkbox.receipt_fiscal_events', [], 'default'))) {
                    Log::info(__METHOD__ . " Платіж [{$payment->id}] оплачено. Запит на фіскалізацію відправлено...");
                }

                return true;
            } elseif ($payment->status === Payment::STATUS_FAILED) {
                $payment->model->doOrder();
            }

        }

        return $payment;
    }
}
