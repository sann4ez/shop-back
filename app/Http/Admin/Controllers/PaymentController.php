<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Requests\PaymentRequest;
use App\Http\Admin\WebDestinations;
use App\Models\Payment;
use App\Support\Payments\PaymentManager;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class PaymentController extends Controller
{
    use WebDestinations;

    public function index(Request $request)
    {
        $payments = Payment::filterable()->with('user', 'model');
        $totalPayments = Payment::filterable($request->all())->select('amount')->get();

        if ($request->has('_print')) {
            return view('admin.payments.inc.print', ['payments' => $payments->get(), 'from' => $request->paid_at_from, 'to' => $request->paid_at_to]);
        }

        return view('admin.payments.index', ['payments' => $payments->paginate(50), 'totalPayments' => $totalPayments]);
    }

    public function create(Request $request)
    {
        return response()->json([
            'html' => view('admin.payments.modals.create')->render()
        ]);
    }

    public function store(PaymentRequest $request)
    {
        $sign = $request->operation === Payment::OPERATION_EXPENSE ? -1 : 1;

        $payment = Payment::create($request->getData() + [
            'amount' => $sign * $request->amount,
            'model_type' => $request->model_id ? 'order' : null,
        ]);

        // TODO: without Order
        if ($request->_make_payment_url) {
            $payManager = new PaymentManager();
            $payManager->doOnlinePay($payment);
        }

        return redirect()->back()
            ->with('success', trans('admin.store.success'));
    }

    public function edit(Request $request, Payment $payment)
    {
        return response()->json([
            'html' => view('admin.payments.modals.edit', compact('payment'))->render()
        ]);
    }

    public function update(PaymentRequest $request, Payment $payment)
    {
        $sign = $request->operation === Payment::OPERATION_EXPENSE ? -1 : 1;

        $payment->update($request->getData() + [
                'amount' => $sign * $request->amount,
                'model_type' => $request->model_id ? 'order' : null,
            ]);

        return redirect()->back()
            ->with('success', trans('admin.update.success'));
    }

    public function destroy(Request $request, Payment $payment)
    {
        $payment->delete();

        return redirect()->back()
            ->with('success', trans('admin.destroy.success'));
    }

    public function editable(Request $request, Payment $payment)
    {
        $request->validate([
            'name' => 'string|required',
            'value' => 'nullable|string',
        ]);

        $payment->setAttribute($request->name, $request->value);
        $payment->save();

        return response()->json(['message' => trans('alerts.update.success')]);
    }

    public function relink(Payment $payment, Request $request): RedirectResponse
    {
        if (method_exists($payment->model, 'isPerformed') && $payment->model?->isPerformed()) {
            return redirect()->back();
        }

        $payment->update([
            'payment_url' => null,
            'payment_url_expires_at' => null,
        ]);

        return redirect()->back()
            ->with('success', trans('admin.update.success'));
    }
}
