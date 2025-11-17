<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Controllers\Controller;
use App\Http\Admin\Requests\OrderRequest;
use App\Http\Admin\WebDestinations;
use App\Models\Order;
use App\Models\User;
use App\Support\Cart\Cart;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

final class OrderController extends Controller
{
    use WebDestinations;

    public function index(Request $request)
    {
        $orders = Order::with('purchases.variation')->filterable($request->all(), ['type' => Order::TYPE_ORDER]);
        $totalOrders = Order::query()->filterable($request->all(), ['type' => 'order'])->select('sum', 'sum_cost', 'profit')->get();

        return view('admin.orders.index', ['orders' => $orders->paginate(), 'totalOrders' => $totalOrders]);
    }

    public function create(Request $request)
    {
        if ($order = $this->orderCartFirstOrCreate()) {
            return $this->edit($order);
        }

        return view('admin.orders.create');
    }

    protected function orderCartFirstOrCreate()
    {
        $order = null;

        if ($orderId = session('order-create')) {
            $order = Order::where('type', Order::TYPE_CART)->find($orderId);
        }

        if (is_null($order)) {
            $order = Order::create([
                'type' => Order::TYPE_CART,
                'source' => 'manager',
                'user_id' => request('user_id'),
            ]);

            session()->put('order-create', $order->id);
        }

        if (($userId = request('user_id')) && $user = User::find($userId)) {
            $order->setAttribute('user_id', $user->id)->saveQuietly();
        }

        return $order;
    }

    /**
     * TODO: Deprecated not used!!!
     *
     * @param OrderRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(OrderRequest $request)
    {
        $order = Order::create($request->getData());

        $order->setAttribute('added->shipping', $request->input('shipping', []));
        $order->setAttribute('added->user', $request->input('user', []));
        $order->setAttribute('added->recipient', $request->input('recipient', []));
        $order->save();

        return redirect()
            ->to($this->destinationUrl(route('admin.orders.edit', $order)))
            ->with('success', trans('alerts.store.success'));
    }

    public function show(Order $order)
    {
        return response()->json([
            'html' => view('admin.orders.modals.show', \compact('order'))->render()
        ]);
    }

    public function edit(Order $order)
    {
        $order->load([
            'purchases.model.product.category',
            'purchases.variationWithTrashed',
            'purchases.variation.product',
            'purchases.variation.properties',
            'purchases.variation.properties.attribute',
        ]);

        return view('admin.orders.edit', compact('order'));
    }

    public function update(OrderRequest $request, Order $order)
    {
        $status = $order->status;
        $perform = $order->perform;

        $order->update($request->getData());

        $order->setAttribute('added->shipping', array_merge($order->getAdded('shipping', []), $request->input('shipping', [])));
        $order->setAttribute('added->recipient', array_merge($order->getAdded('recipient', []), $request->input('recipient', [])));
        $order->setAttribute('added->user', array_merge($order->getAdded('user', []), $request->input('user', [])));
        $order->saveQuietly();

        // виконання Підтверджено/Зарезервовано
        if (in_array($request->perform, [Order::PERFORM_CONFIRMED, Order::PERFORM_PENDING_RESERVED]) && !in_array($perform, [Order::PERFORM_CONFIRMED, Order::PERFORM_PENDING_RESERVED])) {
            $this->ordered($request, $order, $perform);
            $order->refreshStockQty();                  // TODO: знімаємо зі складу, підтверджуємо!

        // Виконання Скасовано (якщо раніш було підтведжено, то тоді знімаємо повертаємо на скад)
        } elseif ($request->perform === Order::PERFORM_CANCELLED && in_array($perform, [Order::PERFORM_PENDING_RESERVED, Order::PERFORM_CONFIRMED, Order::PERFORM_DONE])) {
            $this->ordered($request, $order, $perform);
            $order->refreshStockQty(true); // TODO: вертаємо на склад, скасовуємо!
        }

        // якщо змінилася Знижка, Доставка, і т.д. то оновити.
        $order->setAttribute('sum', $order->totalSum());
        $order->setAttribute('sum_cost', $order->allPurchasesSumCost());
        $order->setAttribute('profit', $order->getProfitSum());
        $order->saveQuietly();

        if ($request->action === 'ordered') {
            $this->ordered($request, $order, $perform);
        }

        return redirect()->route('admin.orders.edit', $order)
            ->with('success', trans('alerts.update.success'));
    }

    /**
     * Корзина стає замовленням, надається номер, дата оформлення.
     *
     * @param Request $request
     * @param Order $order
     * @param $perform
     * @return void
     * @throws ValidationException
     */
    protected function ordered(Request $request, Order $order, $perform)
    {
        if ($order->purchases->count() < 1 && in_array($perform, [Order::PERFORM_PENDING_RESERVED, Order::PERFORM_CONFIRMED, Order::PERFORM_DONE])) {
            throw ValidationException::withMessages(['purchases' => 'В замовлення не додано товарів. Для офрмлення потрібно додати товар!']);
        }

        \Cart::setOrder($order)->checkout(array_merge(['ordered' => true], $request->only('status', 'user_id')));

        if (session('order-create') === $order->id) {
            session()->forget('order-create');
        }
    }

    /**
     * @param Order $order
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->back()
            ->with('success', trans('alerts.destroy.success'));
    }

    public function editable(Request $request, Order $order)
    {
        $request->validate([
            'name' => 'string|required',
            'value' => 'nullable|string',
        ]);

        if (in_array($request->name, ['manager_comment'])) {
            $order->setAttribute($request->name, $request->value);
        } else {
            //$order->setAttribute('added->'.$request->name, $request->value);
        }
        $order->save();

        return response()->json(['message' => trans('alerts.update.success')]);
    }

    public function printed(Request $request, Order $order)
    {
        if ($request->get('_format') == 'pdf') {
            $pdf = Pdf::loadView('admin.orders.print', ['order' => $order->load('purchases.model')]);
            $pdf->setPaper('A4', )
                ->setOptions(['defaultFont' => 'DejaVu Sans']);

            $ts = now()->timestamp;
            return $pdf->download("order-{$order->number}-{$ts}.pdf");
        }

        return view('admin.orders.print', ['order' => $order]);
    }
}
