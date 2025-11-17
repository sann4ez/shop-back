<?php

namespace App\Http\Admin\Controllers;

use App\Models\Payment;
use App\Models\Order;
use App\Support\Payments\PaymentManager;
use Illuminate\Http\Request;

class OrderPaymentController extends Controller
{
    public function create(Request $request, Order $order)
    {
        $gateway = Payment::gatewaysList('*', 'key', ['added' => [\App\Models\Payment::GATEWAY_REQUISITE, \App\Models\Payment::GATEWAY_CASH,]])[$request->gateway];

        return response()->json([
            'html' => view('admin.payments.modals.create', compact('gateway', 'order'))
                ->render()
        ]);
    }

    public function createExpense(Request $request, Order $order)
    {
        return response()->json([
            'html' => view('admin.payments.modals.create-expense', compact('order'))
                ->render()
        ]);
    }

    public function store(Request $request, Order $order)
    {
        $operation = $request->operation ?: Payment::OPERATION_INCOME;
        $sign = $operation === Payment::OPERATION_EXPENSE ? -1 : 1;

        $payment = Payment::create($request->only('status', 'is_guarantee', 'comment', 'source', 'category', 'user_id') + [
            'amount' => $request->amount * $sign,
            'gateway' => $request->gateway ?: Payment::GATEWAY_REQUISITE,
            'currency_code' => $order->currency_code,
            'operation' => $operation,
            'model_type' => $order->getMorphClass(),
            'model_id' => $order->id,
        ]);

        if ($request->_make_payment_url) {
            $payManager = new PaymentManager();
            $payManager->doOnlinePay($payment);
        }

        return redirect()->back()
            ->with('success', trans('alerts.store.success'));
    }

    public function edit(Request $request, Payment $payment)
    {
        $gateway = Payment::gatewaysList('*', 'key')[$payment->gateway];
        $order = $payment->model;

        return response()->json([
            'html' => view('admin.leads.modals.edit', compact('gateway', 'payment', 'order'))
                ->render()
        ]);
    }

    public function update(Request $request, Payment $payment)
    {
        $operation = $request->operation ?: Payment::OPERATION_INCOME;
        $sign = $operation === Payment::OPERATION_EXPENSE ? -1 : 1;

        $payment->update($request->only('gateway', 'status', 'is_guarantee', 'comment') + [
                'amount' => $request->amount * $sign,
        ]);

        return redirect()->back()
            ->with('success', trans('alerts.update.success'));
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->back()
            ->with('success', trans('alerts.destroy.success'));
    }

    public function editable(Request $request, Payment $payment)
    {
        $request->validate([
            'name' => 'string|required',
            'value' => 'nullable|string',
        ]);

        $payment->setAttribute($request->name, $request->value);
        $payment->save();

        if (!$request->ajax()) {
            return \redirect()
                ->back()->with('success', trans('alerts.update.success'));
        }

        return response()
            ->json(['message' => trans('alerts.update.success'), 'operation' => $request->_operation]);
    }
}
