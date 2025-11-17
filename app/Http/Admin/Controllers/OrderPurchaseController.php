<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\Purchase;
use Illuminate\Http\Request;

final class OrderPurchaseController extends Controller
{
    public function add(Request $request, Order $order)
    {
        foreach ($request->variations ?? [] as $id => $data) {
            /** @var ProductVariation $productVariation */
            $productVariation = ProductVariation::find($id);

            /** @var Purchase $purchase */
            $purchase = $order->purchases()->create([
                'price' => $data['price'],
                'quantity' => $data['qty'],
                'discount' => $data['discount'],
            ]);
            $purchase->freshProductData($productVariation);
        }

        if ($request->ajax()) {
            return \redirect()->back()->with('success', trans('alerts.store.success'));
        }

        return redirect()
            ->route('admin.orders.edit', $order)
            ->with('success', trans('alerts.store.success'));
    }

    public function destroy(Purchase $purchase)
    {
        if ($purchase->order->isPerformed()) {
            return redirect()
                ->route('admin.orders.edit', $purchase->order)
                ->with('error', trans('alerts.operation.unallowed'));
        }

        $purchase->delete();

        return redirect()
            ->route('admin.orders.edit', $purchase->order)
            ->with('success', trans('alerts.destroy.success'));
    }

    public function editable(Request $request, Purchase $purchase)
    {
        $request->validate([
            'name' => 'string|required',
            'value' => 'string|required',
        ]);
        /** @var Order $order */
        $order = $purchase->order;

        if ($order->isPerformed()) {
            return response()
                ->json(['message' => trans('alerts.operation.unallowed'), 'status' => 'error']);
        }

        $purchase->setAttribute($request->name, $request->value);
        $purchase->save();
        $purchase->freshProductData();

        return response()
            ->json(['message' => trans('alerts.update.success')]);
    }
}
