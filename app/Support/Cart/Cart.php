<?php

namespace App\Support\Cart;

use App\Events\OrderOrdered;
use App\Models\Auth\User;
use App\Models\Item;
use App\Models\Shop\Discount;
use App\Models\Shop\Order;
use App\Models\Shop\ProductVariation;
use App\Models\Shop\Promocode;
use App\Models\Shop\Promotion;
use App\Models\Shop\Purchase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Cart
{
    /** @var string */
    protected string $id = '';

    /** @var Order|null */
    protected $order = null;

    public static $cartKey = 'sCart';

    protected $orderRelations = [
        'purchases.model.translations',                         // для виведення назви, ціни, sku
        'discounts.promotion',                                  // для обчислення знижки
        'purchases.model.product.translations',                 // для виведення назви...
        //'purchases.model.product.category',   // для виведення урл на варіант
        //'purchases.model.properties.attribute', // для getAttributesPropertiesListStr()
        //'purchases.model.product.media',
//        'purchases.variation.media',
        // 'purchases.variation.product.media',
        //'purchases.model.media',
    ];

    /**
     * @return mixed
     */
    public function getId(): string|null
    {
        if ($this->id) {
            return $this->id;
        }

        $cookieCartId = request()->get(self::$cartKey) ?: request()->header(self::$cartKey) ?: request()->cookie(self::$cartKey);
        $cookieCart = $cookieCartId ? Order::where(['type' => Order::TYPE_CART, 'id' => $cookieCartId])->first() : null;

        if ($user = Auth::user()) {
            $cart = $user->orders()->where([
                'type' => Order::TYPE_CART,
                'domain_id' => \Domain::getId(),
            ])->first();

            if ($cart && $cookieCart && $cart->id !== $cookieCart->id) {
                $cart->purchases()->saveMany($cookieCart->purchases);
                $cookieCart->delete();
            }

            if (is_null($cart) && $cookieCart) {
                // updateOrCreate потрібно для того щоб отримати модель корзини
                $cart = $cookieCart->updateOrCreate([
                    'id' => $cookieCart->id,
                    'user_id' => $user->id,
                ]);
            }

            return $cart?->id ?: $cookieCart?->id ?: null;
        }

        return $cookieCart?->id ?: null;
    }

    public function init(bool $force = false)
    {
        $order = null;

        if ($this->order) {
            return $this;
        }
        // TODO: check && $this->order - maybe remove
        if ($this->getId()/* && $this->order !== false*/) {

            /** @var Order $order */
            if ($order = Order::whereType(Order::TYPE_CART)
                ->whereId($this->getId())->first()) {

                $this->order = $order->loadTrans($this->orderRelations);
            }
        }

        if ($force && is_null($order)) {
            /** @var Order $order */
            $order = Order::create([
                'type' => Order::TYPE_CART,
                'domain_id' => \Domain::getId(),
                'user_id' => Auth::id(),
                'currency_code' => \Domain::getSelected('currency_code')
            ])->loadTrans($this->orderRelations);

            // VISIT
            \App\Actions\VisitAction::run('cart', $order);

            \Illuminate\Support\Facades\Cookie::queue(self::$cartKey, $order->id, 43200);

            $this->order = $order;
            $this->recalculateWithPromo();
        }

        return $this;
    }

    /**
     * @return Order|null
     */
    public function order(): ?Order
    {
        return $this->getOrder();
    }

    /**
     * @return Order|null
     */
    public function getOrder(): ?Order
    {
        $this->init();

        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function setOrder(Order $order): self
    {
        $order->loadTrans($this->orderRelations);
        $this->order = $order;

        return $this;
    }

    /**
     * TODO: isset in Order class, drop?
     * @return Collection
     */
    public function purchases(): Collection
    {
        $this->init();

        return $this->order?->purchases ?: collect();
    }

    /**
     * @param $model
     * @return bool
     */
    public function isAdded($model): bool
    {
        $id = $model instanceof Model ? $model->id : $model;

        return $this->purchases()->contains('model_id', $id);
    }

    public function countAdded($model): int
    {
        $id = $model instanceof Model ? $model->id : $model;

        return $this->purchases()->where('model_id', $id)->first()?->quantity ?: 0;
    }

    /**
     * Добавить товар.
     *
     * @param ProductVariation $variation
     * @param int $quantity
     * @return $this
     */
    public function add(ProductVariation $variation, array $attrs = [])
    {
        $this->init(true);

        $quantity = Arr::get($attrs, 'quantity') ?? 1;

        /** @var Purchase $purchase */
        if ($purchase = $this->order->purchases()->where([
            'model_id' => $variation->id,
            'model_type' => $variation->getMorphClass(),
        ])->first()) {
            $purchase->setAttribute('quantity', $purchase->quantity + $quantity)->save();
        } else {
            $purchase = $this->order->purchases()->create([
                'model_id' => $variation->id,
                'model_type' => $variation->getMorphClass(),
                'quantity' => $quantity,
                'source' => Arr::get($attrs, 'source'),
            ]);
        }

        $purchase->freshProductData();
        $this->order->refresh();
        $this->recalculateWithPromo();

        return $this;
    }

    /**
     * Видалити позицію.
     *
     * @param Purchase $purchase
     * @param int $quantity
     * @return $this
     */
    public function remove(Purchase $purchase, array $attrs = [])
    {
        $this->init();

        $quantity = Arr::get($attrs, 'quantity') ?? 0;

        if ($this->order) {

            if ($quantity > 0 && $purchase->quantity > $quantity) {
                $purchase->setAttribute('quantity', $purchase->quantity - $quantity)->save();

                $purchase->freshProductData();
            } else {
                $purchase->delete();
            }

            $this->order->refresh();
            $this->recalculateWithPromo();
        }

        return $this;
    }

    /**
     * Застосувати промокод.
     *
     * @param Promocode $promocode
     * @param bool $isSingle
     * @return bool
     */
    public function preparePromocode(Promocode $promocode, $isSingle = true)
    {
        $this->init();

        if ($this->order) {

            if (!$promocode?->promotion->isAllowedForUser()) {
                return false;
            }

            // якщо промокод приміняємо, то відмінити інші промокоди цієї ж акції
            $this->order->discounts()
                ->where('promotion_id',  $promocode->promotion_id)
                ->where('promocode_id', '<>', $promocode->id)
                ->delete();

            if ($isSingle) {
                $this->order->discounts()->updateOrCreate([], [
                    'promocode_id' => $promocode->id,
                    'promotion_id' => $promocode->promotion_id,
                ]);
            } else {
                $this->order->discounts()->firstOrCreate([
                    'promocode_id' => $promocode->id,
                ], [
                    'promotion_id' => $promocode->promotion_id,
                ]);
            }
            $this->order->refresh();
            $this->recalculateWithPromo();

            return true;
        }

        return false;
    }

    /**
     * Видалити промокод.
     *
     * @param Promocode $promocode
     * @param bool $isSingle
     * @return bool
     */
    public function removePromocode(Promocode $promocode, $isSingle = true)
    {
        $this->init();

        if ($this->order) {
            /** @var Discount $discount */
            if ($discount = $this->order->discounts->where('promocode_id', $promocode->id)->first()) {

                $discount->delete();

                $this->order->refresh();
                $this->recalculateWithPromo();

                return true;
            }
        }

        return false;
    }

    /**
     * Перерахувати ціну замовлення, позицій.
     *
     * @return bool|void
     */
    protected function recalculateWithPromo()
    {
        if ($this->order) {

            $deliverySum = \Variable::getArray('shipping.postal.price', 0, \Domain::getGroup());

            $discountSum = 0;
            $deliveryDiscountSum = 0;
            $isFreeDelivery = false;
            $hasPromotion = false;

            if ($this->order->purchases->count() < 1) {
                $this->order->fill([
                    'discount_sum' => 0,
                    'delivery_sum' => 0,
                    'delivery_discount_sum' => 0,
                ]);

                $this->order->setAttribute('added->is_free_delivery', false);

                $this->order->setAttribute('added->has_promotion', false);

                $this->order->save();

                $this->order->discounts()->delete();

                return true;
            }

            // Варіації (екземпляри), які уже взяли участь в знижці
            $variationsAlreadyHasDiscountIds = [];

            $promotions = Promotion::isActive()->with('variations:id', 'terms:id')
                ->select('id', 'added', 'discount_type', 'discount', 'type')
                ->get();
                //->filter(fn(Promotion $p) => $p->isAllowedForUser());

            // TODO check: Зжижки при покупці кількості екземпляра варіації
            foreach ($this->order->purchases as $purchase) {
                foreach ($promotions->where('type', Promotion::TYPE_DISCOUNT_VARIATION_COUNT) as $promotion) {
                    if ($discounts = $promotion->added['conditions'] ?? []) {
                        usort($discounts, function ($item1, $item2) {
                            return $item2['count'] <=> $item1['count'];
                        });
                        if ($promotion->variations->contains('id', $purchase->model_id)) {
                            foreach ($discounts as $discountData) {

                                if ($purchase->quantity >= $discountData['count']) {
                                    if ($promotion->discount_type === Promotion::DISCOUNT_TYPE_SUM) {
                                        $discountVal = $discountData['discount'] ?? 0;
                                        //$discountSum = $discountSum + $discountVal;
                                        $purchase->setAttribute('discount', $discountVal)->save();
                                        $variationsAlreadyHasDiscountIds[] = $purchase->model_id;

                                        $hasPromotion = true;
                                    } elseif ($promotion->discount_type === Promotion::DISCOUNT_TYPE_PERCENT) {
                                        $discountVal = ($purchase->price - ($purchase->price - ($discountData['discount'] ?? 0) * $purchase->price / 100));
                                        //$discountSum = $discountSum + $discountVal;
                                        $purchase->setAttribute('discount', $discountVal)->save();
                                        $variationsAlreadyHasDiscountIds[] = $purchase->model_id;

                                        $hasPromotion = true;
                                    }
                                    break;
                                }
                            }

                        }
                    }
                }
            }

            // Знижка на замовлення при покупці сумарної кількості вказаних варіацій
            /** @var Promotion $promotion */
            foreach ($promotions->where('type', Promotion::TYPE_DISCOUNT_VARIATIONS_COUNT) as $promotion) {

                if ($discounts = $promotion->added['conditions'] ?? []) {
                    usort($discounts, function ($item1, $item2) {
                        return $item2['count'] <=> $item1['count'];
                    });

                    $variationsIds = ProductVariation::query()->byPromotionVariations($promotion)->select('id')->get()->pluck('id')->toArray();

                    $purchases = $this->order->purchases
                        ->whereNotIn('model_id', $variationsAlreadyHasDiscountIds)
                        ->whereIn('model_id', $variationsIds);
                    $quantity = $purchases->sum('quantity');
                    $sum = $purchases->sum(fn($p) => $p->price * $p->quantity);

                    if ($quantity < 1) {
                        break;
                    }

                    $avgPrice = $sum/* / $quantity*/;

                    foreach ($discounts as $discountData) {
                        if ($quantity >= $discountData['count']) {
                            if ($promotion->discount_type === Promotion::DISCOUNT_TYPE_SUM) {
                                $discountVal = $discountData['discount'] ?? 0;
                                $discountSum = $discountSum + $discountVal;
                                $variationsAlreadyHasDiscountIds = array_merge($variationsAlreadyHasDiscountIds, $purchases->pluck('model_id')->toArray());

                                $hasPromotion = true;
                            } elseif ($promotion->discount_type === Promotion::DISCOUNT_TYPE_PERCENT) {
                                $discountVal = ($avgPrice - ($avgPrice - ($discountData['discount'] ?? 0) * $avgPrice / 100));
                                $discountSum += $discountSum + $discountVal;
                                $variationsAlreadyHasDiscountIds = array_merge($variationsAlreadyHasDiscountIds, $purchases->pluck('model_id')->toArray());

                                $hasPromotion = true;
                            }
                            break;
                        }
                    }
                }
            }

            // Знижка на суму замовлення при мінімальній сумі товарів замовлення
            foreach ($promotions->where('type', Promotion::TYPE_DISCOUNT_ORDER_SUM) as $promotion) {
                $sum = $this->order->purchases->sum(fn($p) => $p->price * $p->quantity);

                if ($sum >= ($promotion->added['conditions']['sum'] ?? 0)) {
                    if ($promotion->discount_type === Promotion::DISCOUNT_TYPE_SUM) {
                        $discountSum += $promotion->discount;

                        $hasPromotion = true;
                    } elseif ($promotion->discount_type === Promotion::DISCOUNT_TYPE_PERCENT) {
                        $discountSum = $discountSum + ($sum * $promotion->discount / 100);

                        $hasPromotion = true;
                    }
                    break;
                }
            }

            // Безкоштовна доставка на суму замовлення при мінімальній сумі товарів замовлення
            foreach ($promotions->where('type', Promotion::TYPE_FREE_DELIVERY_ORDER_SUM) as $promotion) {
                $sum = $this->order->purchases->sum(fn($p) => $p->price * $p->quantity);

                if ($sum >= ($promotion->added['conditions']['sum'] ?? 0)) {
                    $isFreeDelivery = true;

                    $hasPromotion = true;
                    break;
                }
            }

            // Знижки застосовані промокодами
            /** @var Discount $discount */
            foreach ($this->order->discounts as $discount) {

                if (($discount->promocode->used_limit > 0) && (!$discount->promocode?->isAllowed())) {
                    $discount->delete();
                    continue;
                }

                /** @var Promotion $promotion */
                $promotion = $discount->promotion;

                // Безкоштована доставка
                if (in_array($promotion->type, [Promotion::TYPE_FREE_DELIVERY_CODE, Promotion::TYPE_FREE_DELIVERY_CODE_RULES])) {
                    $deliverySum = $deliverySum ?: $this->order->delivery_sum;
                    $deliveryDiscountSum = $deliverySum;

                    $isFreeDelivery = true;

                    $hasPromotion = true;
                }

                // Знижка на вартість доставки
                if ($promotion->type === Promotion::TYPE_DISCOUNT_DELIVERY_CODE) {
                    if ($promotion->discount_type === Promotion::DISCOUNT_TYPE_SUM) {
                        $deliveryDiscountSum += $promotion->discount;

                        $hasPromotion = true;
                    } elseif ($promotion->discount_type === Promotion::DISCOUNT_TYPE_PERCENT) {
                        $deliveryDiscountSum += $deliverySum * $promotion->discount / 100;

                        $hasPromotion = true;
                    }
                }

                // Фіксована знажка на замовлення (на суму товарів в замовленні)
                elseif ($promotion->type === Promotion::TYPE_DISCOUNT_ORDER_CODE) {
                    if ($promotion->discount_type === Promotion::DISCOUNT_TYPE_SUM) {
                        if ($this->order->purchasesSum() > ($discountSum + $promotion->discount)) {
                            $discountSum = $discountSum + $promotion->discount;

                            $hasPromotion = true;
                        }
                    } elseif ($promotion->discount_type === Promotion::DISCOUNT_TYPE_PERCENT) {
                        $discountSum = $discountSum + ($this->order->purchasesSum() * $promotion->discount / 100);

                        $hasPromotion = true;
                    }
                }
            }

            // Безкоштовна доставка на період
            foreach ($promotions->where('type', Promotion::TYPE_FREE_DELIVERY_DATE) as $promotion) {
                $isFreeDelivery = true;

                $hasPromotion = true;
            }

            $this->order->fill([
                'discount_sum' => $discountSum,
                'delivery_sum' => $deliverySum ?: $this->order->delivery_sum,
                'delivery_discount_sum' => $deliveryDiscountSum,
            ]);

            $this->order->setAttribute('added->is_free_delivery', $isFreeDelivery);

            $this->order->setAttribute('added->has_promotion', $hasPromotion);

            $this->order->save();
            //$this->order->refresh();
            return true;
        }
    }

    /**
     * Отримати промокод (перший з списку).
     *
     * @return null
     */
    public function promocode(string $column = null)
    {
        $this->init();

        if ($this->order) {
            if ($discount = $this->order->discounts->whereNotNull('promocode_id')->first()) {
                if ($promocode = $discount->promocode) {
                    return $column ? $promocode->{$column} : $promocode;
                }
            }
        }

        return null;
    }

    /**
     * Оновлення позиций.
     *
     * @param array $data
     * @return int[]
     */
    public function sync(array $data = [])
    {
        $this->init(true);

        $report = [
            'removed' => 0,
            'changed' => 0,
            'added' => 0,
            'changelog' => [
                'added' => [],
                'missed' => [],
            ],
        ];

        if (!$this->order) {
            return $report;
        }

        $removed = array_merge(Arr::get($data, 'removed', []), Arr::get($data, 'deleted', [])); // `deleted` is deprecated!
        foreach ($removed as $id) {
            if ($this->order->purchases()->where('id', $id)->delete()) {
                $report['removed']++;
            }
        }

        foreach (Arr::get($data, 'changed', []) as $item) {
            /** @var Purchase $purchase */
            if (($id = Arr::get($item, 'id')) && ($purchase = $this->order->purchases()->find($id))) {
                $oldQuantity = $purchase->quantity;
                $newQuantity = Arr::get($item, 'quantity');
                $missedQuantity = 0;

                if ($newQuantity == 0) {
                    $purchase->delete();
                    $report['removed']++;
                } else {
                    $variation = $purchase->model;
                    $maxAvailable = $variation->stock_qty;

                    // Якщо кількість, яку хочуть добавити перебільшує наявну, то беремо максимально можливу
                    if ($newQuantity > $maxAvailable) {
                        $missedQuantity = $newQuantity - $maxAvailable;
                        $newQuantity = $maxAvailable;
                    }

                    // TODO quantity_prev!
                    if ($newQuantity !== $oldQuantity) {
                        $purchase->update(['quantity' => $newQuantity]);
                        $purchase->freshProductData();
                        $report['changed']++;
                    }

                    if (($newQuantity - $oldQuantity)  > 0) {
                        $report['changelog']['added'][] = [
                            'id' => $purchase->model->id,
                            'name' => $purchase->model->getName(),
                            'quantity' => $newQuantity - $oldQuantity,
                        ];
                    }

                    if ($missedQuantity > 0) {
                        $report['changelog']['missed'][] = [
                            'id' => $purchase->model->id,
                            'name' => $purchase->model->getName(),
                            'quantity' => $missedQuantity,
                        ];
                    }
                }
            }
        }

        foreach (Arr::get($data, 'added', []) as $item) {
            if ($id = Arr::get($item, 'id')) {
                if ($variation = ProductVariation::find($id)) {
                    $requestedQuantity = Arr::get($item, 'quantity', 1);
                    $availableStock = $variation->stock_qty;
                    $missedQuantity = 0;

                    // Визначаємо фактично можливу кількість
                    $quantityToAdd = min($requestedQuantity, $availableStock);

                    if ($quantityToAdd < $requestedQuantity) {
                        $missedQuantity = $requestedQuantity - $quantityToAdd;
                    }

                    if ($quantityToAdd > 0) {
                        /** @var Purchase $purchase */
                        $purchase = $this->order->purchases()->where([
                            'model_id' => $variation->id,
                            'model_type' => $variation->getMorphClass(),
                        ])->first();

                        if ($purchase) {
                            $previousQuantity = $purchase->quantity;
                            $purchase->update(['quantity' => $previousQuantity + $quantityToAdd]);
                        } else {
                            $purchase = $this->order->purchases()->create([
                                'model_id' => $variation->id,
                                'model_type' => $variation->getMorphClass(),
                                'quantity' => $quantityToAdd,
                            ]);
                        }

                        $purchase->freshProductData();
                        $report['added']++;

                        $report['changelog']['added'][] = [
                            'id' => $purchase->model->id,
                            'name' => $variation->getName(),
                            'quantity' => $quantityToAdd,
                        ];
                    }

                    if ($missedQuantity > 0) {
                        $report['changelog']['missed'][] = [
                            'id' => $variation->id,
                            'name' => $variation->getName(),
                            'quantity' => $missedQuantity,
                        ];
                    }
                } else {
                    Log::error(__METHOD__ . " Variation id [{$id}] not found!");
                }
            }
        }

        $this->order->refresh();
        $this->recalculateWithPromo();

        return $report;
    }

    /**
     * Очистить корзину.
     *
     * @return $this
     */
    public function clear()
    {
        $this->init();

        if ($this->order) {
            $this->order->purchases()->delete();
            $this->recalculateWithPromo();
        }

        return $this;
    }

    public function __call($name, $arguments)
    {
        $this->init();

        return $this->order ? $this->order->{$name}(...$arguments) : 0;
    }

//    public const ERROR_EMPTY = 'empty';
//    public const ERROR_MIN_QTY = 'min_qty';
//    public const ERROR_MIN_SUM = 'min_sum';
//    public const ERROR_QTY_PRODUCT = 'qty_product';
//    public const ERROR_MULTIPLICITY_PURCHASE = 'multiplicity_purchase';

    /**
     * @return array|bool|string[]
     */
    public function validateCheckout()
    {
        $this->init();

        // порожність корзини
        if (empty($this->order) || $this->order->quantity() < 1) {
            return [
                'message' => trans('alerts.cart.empty'),
                'status' => 'error',
            ];
        }


        // Валідація на кількості позицій і сум в корзині для ролей юзера
        if (\Domain::getOpt('shop.roles_qty')) {
            $userRole = auth()->user()?->roles->first()->name ?: User::ROLE_GUEST; // TODO
            $group = \Domain::getSelected('id');

            // мінімальна кількість в корзині
            $minQty = \Variable::getArray("shop.{$userRole}.cart.min_qty", 0, $group) || \Domain::getOpt('orders.min_qty');
            if ($minQty > 0) {
                if ($this->quantity() < $minQty) {
                    return [
                        'message' => trans('alerts.cart.min_qty', ['value' => $minQty]),
                        'status' => 'error',
                    ];
                }
            }

            // мінімальна сума позицій в корзині
            $minSum = \Variable::getArray("shop.{$userRole}.cart.min_sum", 0, $group) || \Domain::getOpt('orders.min_sum');
            if ($minSum > 0) {
                if ($this->purchasesSum() < $minSum) {
                    return [
                        'message' => trans('alerts.cart.min_sum', ['value' => $minQty]),
                        'status' => 'error',
                    ];
                }
            }
        }

        // СКЛАД
        // кількості
        if (\Domain::getOpt('warehouses.on')) {

            $res = [];
            // кратність
            foreach ($this->purchases() as $purchase) {
                if ($purchase->getQty() % $purchase->model->getStep() !== 0) {
                    $res['multiplicity'][] = [
                        'id' => $purchase->model->id,
                        'name' => $purchase->model->getName(),
                        'value' => $purchase->model->getStep(),
                    ];
                }
            }

            // мінімальна обовязкова кількість
            foreach ($this->purchases() as $purchase) {
                if ($purchase->quantity < $purchase->model->getMinQty()) {
                    $res['min_qty'][] = [
                        'id' => $purchase->model->id,
                        'name' => $purchase->model->getName(),
                        'value' => $purchase->model->getMinQty(),
                    ];
                }
            }

            // наявна доступна кількість
            foreach ($this->purchases() as $purchase) {
                if ($purchase->model->getQty() < $purchase->getQty()) {
                    $res['qty'][] = [
                        'id' => $purchase->model_id,
                        'name' => $purchase->model->getName(),
                        'value' => $purchase->model->getQty(),
                    ];
                }
            }

            if ($res) {
                $msg = trans('alerts.cart.invalid') . ' ';
//                if (!empty($res['qty'])) {
//                    $msg .= 'Не достатня наявность товарів на складі: ' . implode('; ', Arr::pluck($res['qty'], 'name'));
//                }
//                if (!empty($res['multiplicity'])) {
//                    $msg .= 'Не доступна кратність товарів: ' . implode('; ', Arr::pluck($res['multiplicity'], 'name'));
//                }
//                if (!empty($res['min_qty'])) {
//                    $msg .= 'Не достатня мінімальна к-сть товарів в корзині: ' . implode('; ', Arr::pluck($res['multiplicity'], 'name'));
//                }

                return [
                    'message' => $msg,
                    'status' => 'error',
                    'res' => $res,
                ];
            }
        }

        return true;
    }

    /**
     * Підтвертиди оформлення замовлення.
     *
     * @param array $data
     * @return Order|false|null
     */
    public function checkout(array $data = [])
    {
        $this->init();

        if ($order = $this->order) {
            if (empty($order->number)) {
                $order->setAttribute('number', $this->makeOrderNumber());
            }
            if ($status = Arr::get($data, 'status', Item::getSettingsValue(Item::TYPE_ORDER_STATUS, Item::SETTINGS_CART_TRANSIT_TO_ORDER, 'pending'))) {
                $order->setAttribute('status', $status);
            }

            $order->setAttribute('currency_code', $order->domain?->currency_code ?? 'UAH');
            if (empty($order->locale_code)) {
                $order->setAttribute('locale_code', Arr::get($data, 'locale_code', \Domain::getLocale()));
            }
            if (empty($order->user_id) || Arr::has($data, 'user_id')) {
                $order->setAttribute('user_id', Arr::get($data, 'user_id'));
            }
            if ($val = Arr::get($data, 'client_comment')) {
                $order->setAttribute('client_comment', $val);
            }
            if (Arr::get($data, 'shipping', [])) {
                $order->setAttribute('added->shipping', Arr::get($data, 'shipping', []));
            }
            if (Arr::get($data, 'recipient', [])) {
                $order->setAttribute('added->recipient', Arr::get($data, 'recipient', []));
            }
            if (Arr::get($data, 'user', [])) {
                $order->setAttribute('added->user', Arr::get($data, 'user', []));
            }
            if (Arr::get($data, 'server', [])) {
                $order->setAttribute('added->server', Arr::get($data, 'server', []));
            }

            $order->saveQuietly();

            if (is_null($order->ordered_at) && !Arr::get($data, 'ordered')) {
                OrderOrdered::dispatch($order);
            }

            // в корзині натиснуто Перейти до оплати
            if (Arr::get($data, 'preordered')) {
                $order->setAttribute('ordered_at', now());
                $order->saveQuietly();
            }

            // Оформлення через адмінку
            if (Arr::get($data, 'ordered')) {
                $order->doOrder();
            }

            foreach ($order->discounts->whereNotNull('promocode_id') as $discount) {
                // якщо був раз використаний (used_count), то другий раз не можна уже
                if ($discount->promocode_used === 0) {
                    $discount->increment('promocode_used');
                    $discount->promocode->increment('used_count');
                }
            }

            $this->order = null;
            $this->id = '';

            if (Arr::get($data, 'ordersending') && Arr::get($data, 'shipping.method')) {
                $ordersending = $order->ordersendings()->firstOrCreate(['service' => Arr::get($data, 'shipping.method')], []);

                if ($order->isFreeDelivery()) {
                    if (in_array($ordersending->service, ['novaposhta', 'novaposhta_locker', 'novaposhta_courier'])) {
                        $ordersending->setAttribute('params->PayerType', 'Sender');
                        $ordersending->save();
                    }
                    elseif (in_array($ordersending->service, ['ukrposhta'])) {
                        $ordersending->setAttribute('params->paidByRecipient', false);
                        $ordersending->save();
                    }
                }
            }

            return $order;
        }

        return false;
    }

    public function makeOrderNumber($varGroup = null): string
    {
        $varGroup = $varGroup ?:\Domain::getSelected('id');

        $number = intval(\Variable::useCache(false)->get('shop_orders', 1000, $varGroup)) + 1;
        \Variable::save('shop_orders', $number, $varGroup);

        $template = trim(\Domain::getOpt('orders.number_template', ''));
        if (strpos($template, '[number]') !== false) {
            $number = str_replace('[number]', $number, $template);
        } elseif ($template) {
            $number = $template . $number;
        }

        return $number;
    }
}
