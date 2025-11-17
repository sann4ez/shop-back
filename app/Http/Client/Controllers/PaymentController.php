<?php

namespace App\Http\Client\Controllers;

use App\Models\Payment;
use App\Models\Order;
use App\Support\Payments\PaymentManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

final class PaymentController extends Controller
{
    /**
     * Перенаправлення клієнта на оплату замовлення.
     *
     * @param Request $request
     * @param Payment $payment
     * @return RedirectResponse
     */
    public function redirectToPay(Request $request, Payment $payment)
    {
        /** @var Order $order */
        $order = $payment->model;

        $request->mergeIfMissing(['order_number' => $order?->number]);

        // Якщо даний платіж оплачено - редіректимо клієнта на сторінку успішної оплати
        if ($payment->isPaid()) {
            return $this->info($request, 'progress');
        }

        $res = (new PaymentManager)->doOnlinePay($payment);

        // Редірект на платіжну
        if ($res['status'] === 'success' && Arr::get($res, 'res.url')) {
            $destination = Arr::get($res, 'res.url');

            return redirect()->to($destination);
        }

        return $this->info($request, 'cancel');
    }

    public function order(Request $request, Order $order)
    {
        if ($url = $order->getPaymentUrl()) {
            return redirect()->to($url);
        }

        abort(404);
    }

    /**
     * Сторінка результату платежа (для redirect з платіжної)
     *
     * @param Request $request
     * @param string|null $result
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function info(Request $request, string $result = null)
    {
        if (!app()->environment('production')) {
            Log::info(__METHOD__, ['res' => $result, 'request' => $request->all()]);
        }

        // Сторінка (URL) задані в налаштуваннях
        if ($url = \Variable::getArray("payments.pages.{$result}", null)) {
            return redirect()->to(url_add_params($url, $request->only('order_number')));
        }

        if ($result === 'cancel') {
            return view()->first(['payments.cancel', 'client.common.payments.cancel'], ['message' => 'Помилка оплати! Зверніться до підтримки сайту.']);
        }

        return view()->first(['payments.progress', 'client.common.payments.progress'], ['message' => 'Дякуємо! Платіж успішно відправлено.']);
    }
}
