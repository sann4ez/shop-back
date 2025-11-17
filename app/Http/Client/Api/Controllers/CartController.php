<?php

namespace App\Http\Client\Api\Controllers;

use App\Actions\StoreUserAction;
use App\Http\Client\Api\Resources\OrderResource;
use App\Http\Client\Api\Resources\PurchaseResource;
use App\Http\Client\Requests\CartCheckoutRequest;
use App\Models\User;
use App\Models\Order;
use App\Models\ProductVariation;
use App\Models\Purchase;
use App\Support\Cart\Cart;
use App\Support\Payments\PaymentManager;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

final class CartController extends Controller
{
    /**
     * @api {get} /api/cart 01. Товари корзини
     * @apiVersion 1.0.0
     * @apiName CartPurchases
     * @apiGroup ShopCart
     *
     * @apiDescription Виводити наприклад в header сайту.
     *
     * @apiHeader {String} sCart Cart ID: `b8bbd23a-44b4-4a0e-8641-75993175b3a2`
     *
     * @apiDescription Якщо корзина пуста, то повертається обєкт: `{data:null, total:null}`
     * @apiParam {String} [sku] Рядок SKUs (через кому) товарів які автоматом додати до корзини при даному запиті
     * @apiParam {String} [source] Назва джерела, наприклад telegram, facebook
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *  {
     *      "data": {
     *          "id": "3b3e9524-5cd6-4830-9df4-78491b1f76eb",
     *          "user": null, // На цьому роуті завжди null
     *          "shippings": { // Збережена доставка саме із цього замовлення (не із кабінету користувача)
     *              "method": "novaposhta",
     *              "novaposhta": {
     *                  "CityRef": "db5c893b-391c-11dd-90d9-001a92567626",
     *                  "CityName": "Луцьк",
     *                  "WarehouseRef": "40498332-e1c2-11e3-8c4a-0050568002cf",
     *                  "WarehouseName": "Відділення №11 (вул. Кривий Вал)"
     *              }
     *          },
     *          "payment": { // Збережений спосіб оплати саме із цього замовлення (не із кабінету користувача)
     *              "gateway": "monobank"
     *          }
     *          "purchases": [
     *              {
     *                  "id": "bfecc1a3-4396-453b-9411-0af0e520720d",
     *                  "price": 220,
     *                  "quantity": 2,
     *                  "discount": 0,
     *                  "total": 440,
     *                  "variation": {
     *                      "id": "e0b95634-7994-4160-b4bf-cc0d3102493c",
     *                      "slug": "lasoshchi-dlia-khvostatoho-100",
     *                      "name": "Ласощі для вухатого",
     *                      "sku": "5000020",
     *                      "status": "published",
     *                      "multiplicity": 1,
     *                      "stock_qty": 145,
     *                      "min_qty": 1,
     *                      "prices": {
     *                          "now": 220,
     *                          "old": 300,
     *                          "discount": 80,
     *                          "promotion": null,
     *                          "desc": "Product New/Old price",
     *                          "product": 220,
     *                          "currency": {
     *                              "code": "UAH",
     *                              "symbol": "₴"
     *                          },
     *                          "discount_percent": 27
     *                      },
     *                      "states": {
     *                          "is_favorite": false,
     *                          "in_cart": true,
     *                          "count_cart": 2,
     *                          "in_comparison": false
     *                      },
     *                      "markers": [],
     *                      "images": [
     *                          {
     *                              "id": "2051a794-7111-42e3-9dc1-63671fc739c4",
     *                              "name": "image (1)",
     *                              "url": "https://dropshop.demka.online/storage/2051a794-7111-42e3-9dc1-63671fc739c4/image-1.png",
     *                              "conversions": [
     *                                 //...
     *                              ]
     *                          }
     *                      ],
     *                      "product": {
     *                          "id": "1b7dc8f4-3415-43a0-af4a-a978c5f14bfb",
     *                          "rating": 4.4,
     *                          "comments_count": 5,
     *                          "income_at": "2025-04-30T12:44:24.000000Z",
     *                          "category": {
     *                              "id": "7d5c2d6d-4d28-4c95-b519-f76a9d59c763",
     *                              "name": "Для котів",
     *                              "slug": "dlia-kotiv",
     *                              "variations_count": 10
     *                          },
     *                          "fields": {
     *                              "protein": "Яловичина"
     *                          }
     *                      },
     *                      "specification": [
     *                          {
     *                              "attribute": {
     *                                  "id": "7f96f713-6d15-498e-b450-13d2100766b6",
     *                                  "slug": "vaha",
     *                                  "name": "Вага",
     *                                  "format": "text"
     *                              },
     *                              "properties": [
     *                                  {
     *                                      "id": "6140aea5-ed96-42ea-b92e-4cb1f5910585",
     *                                      "slug": "100-h",
     *                                      "value": "100 г",
     *                                      "color": null
     *                                  }
     *                              ]
     *                          }
     *                      ]
     *                  }
     *              }
     *          ]
     *      },
     *      "total": {
     *          "quantity": 2,                  // Кількість всього товарів
     *          "quantity_unique": 1,           // Кількість унікальних одиниць товарів
     *          "purchases_all": 440,           // Сума за товари
     *          "purchases_discount": 0,        // Знижка за товари
     *          "purchases": 440,               // Сума за товари остаточна (з врахуванням знижок)
     *          "delivery_all": 0,              // Доставка
     *          "delivery_discount": 0,         // Знижка за доставку
     *          "delivery": 0,                  // Доставка остаточна (з врахуванням знижок)
     *          "order_discount": 0,            // Знижка додаткова на замовлення (акційна + кастомна)
     *          "difference_old_prices": 160,   // Різниця старої ціни товарів від поточної ціни позицій в корзині/замовленні (інформаційне значення!)
     *          "sum_old_prices": 600,          // Сума старих цін (інформаційне значення!)
     *          "total_discount": 0,            // Знижка сумарна (товари + доставка + замовлення)
     *          "total": 440                    // Сума остаточна (до оплати)
     *      },
     *      "sblocks": [],
     *  }
     */
    public function purchases(Request $request, Cart $cart)
    {
        $this->addProductsFromIds($request, $cart);

        $res = [
            'data' => null,
            'total' => null,
            'sblocks' => null,
        ];

        if (($order = $cart->order()) && $order->purchases()->count()) {
            $res = array_merge($res, $this->getCartData($order));
        }

        return $res;
    }

    /**
     * @api {get} /api/cart/checkout 02. Сторінка оформлення замовлення
     * @apiVersion 1.0.0
     * @apiName CartCheckoutForm
     * @apiGroup ShopCart
     *
     * @apiHeader {String} sCart Унікальний ідентифікатор корзини. Видається при додаванні першого товара
     *
     * @apiDescription Детальна дані корзини (товари, способи доставки, оплати) - для сторінки офомлення замовлення.
     * Якщо корзина пуста, то повертається обєкт: `{data:null, total:null}`
     *
     * @apiParam {String} [sku] Рядок SKUs (через кому) товарів які автоматом додати до корзини при даному запиті
     * @apiParam {String} [source] Назва джерела, наприклад telegram, facebook, з якого перейшли на сайт, роблять замовлення
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *  {
     *      "data": {
     *          "id": "3b3e9524-5cd6-4830-9df4-78491b1f76eb",
     *          "user": {
     *              "id": "f50128a8-12c3-4033-9ee2-2985a2954f4b",
     *              "name": "Тест",
     *              "lastname": "Клієнт",
     *              "middlename": null,
     *              "phone": "380564654984",
     *              "email": "test@gmail.com"
     *          },
     *          "shippings": {
     *              "method": "novaposhta",
     *              "novaposhta": {
     *                  "CityRef": "db5c893b-391c-11dd-90d9-001a92567626",
     *                  "CityName": "Луцьк",
     *                  "WarehouseRef": "40498332-e1c2-11e3-8c4a-0050568002cf",
     *                  "WarehouseName": "Відділення №11 (вул. Кривий Вал)"
     *              }
     *          },
     *          "payment": {
     *              "gateway": "monobank"
     *          }
     *          "purchases": [
     *              {
     *                  "id": "bfecc1a3-4396-453b-9411-0af0e520720d",
     *                  "price": 220,
     *                  "quantity": 2,
     *                  "discount": 0,
     *                  "total": 440,
     *                  "variation": {
     *                      "id": "e0b95634-7994-4160-b4bf-cc0d3102493c",
     *                      "slug": "lasoshchi-dlia-khvostatoho-100",
     *                      "name": "Ласощі для вухатого",
     *                      "sku": "5000020",
     *                      "status": "published",
     *                      "multiplicity": 1,
     *                      "stock_qty": 145,
     *                      "min_qty": 1,
     *                      "prices": {
     *                          "now": 220,
     *                          "old": 300,
     *                          "discount": 80,
     *                          "promotion": null,
     *                          "desc": "Product New/Old price",
     *                          "product": 220,
     *                          "currency": {
     *                              "code": "UAH",
     *                              "symbol": "₴"
     *                          },
     *                          "discount_percent": 27
     *                      },
     *                      "states": {
     *                          "is_favorite": false,
     *                          "in_cart": true,
     *                          "count_cart": 2,
     *                          "in_comparison": false
     *                      },
     *                      "markers": [],
     *                      "images": [
     *                          {
     *                              "id": "2051a794-7111-42e3-9dc1-63671fc739c4",
     *                              "name": "image (1)",
     *                              "url": "https://dropshop.demka.online/storage/2051a794-7111-42e3-9dc1-63671fc739c4/image-1.png",
     *                              "conversions": {
     *                                  "big": {
     *                                      "url": "https://dropshop.demka.online/storage/2051a794-7111-42e3-9dc1-63671fc739c4/conversions/image-1-big.webp"
     *                                  },
     *                                  "thumb": {
     *                                      "url": "https://dropshop.demka.online/storage/2051a794-7111-42e3-9dc1-63671fc739c4/conversions/image-1-thumb.jpg"
     *                                  },
     *                                  "preview": {
     *                                      "url": "https://dropshop.demka.online/storage/2051a794-7111-42e3-9dc1-63671fc739c4/conversions/image-1-preview.webp"
     *                                  }
     *                              }
     *                          }
     *                      ],
     *                      "product": {
     *                          "id": "1b7dc8f4-3415-43a0-af4a-a978c5f14bfb",
     *                          "rating": 4.4,
     *                          "comments_count": 5,
     *                          "income_at": "2025-04-30T12:44:24.000000Z",
     *                          "category": {
     *                              "id": "7d5c2d6d-4d28-4c95-b519-f76a9d59c763",
     *                              "name": "Для котів",
     *                              "slug": "dlia-kotiv",
     *                              "variations_count": 10
     *                          },
     *                          "fields": {
     *                              "protein": "Яловичина"
     *                          }
     *                      },
     *                      "specification": [
     *                          {
     *                              "attribute": {
     *                                  "id": "7f96f713-6d15-498e-b450-13d2100766b6",
     *                                  "slug": "vaha",
     *                                  "name": "Вага",
     *                                  "format": "text"
     *                              },
     *                              "properties": [
     *                                  {
     *                                      "id": "6140aea5-ed96-42ea-b92e-4cb1f5910585",
     *                                      "slug": "100-h",
     *                                      "value": "100 г",
     *                                      "color": null
     *                                  }
     *                              ]
     *                          },
     *                          {
     *                              "attribute": {
     *                                  "id": "53bd4e7e-e340-47b5-a9d5-ad42cd2fbbdb",
     *                                  "slug": "produkt",
     *                                  "name": "Продукт",
     *                                  "format": "text"
     *                              },
     *                              "properties": [
     *                                  {
     *                                      "id": "0098906e-c160-4b90-a559-268124fed4a1",
     *                                      "slug": "funktsionalni-lasoshchi",
     *                                      "value": "Функціональні ласощі",
     *                                      "color": null
     *                                  }
     *                              ]
     *                          },
     *                      ]
     *                  }
     *              }
     *          ]
     *      },
     *      "total": {
     *          "quantity": 2,                  // Кількість всього товарів
     *          "quantity_unique": 1,           // Кількість унікальних одиниць товарів
     *          "purchases_all": 440,           // Сума за товари
     *          "purchases_discount": 0,        // Знижка за товари
     *          "purchases": 440,               // Сума за товари остаточна (з врахуванням знижок)
     *          "delivery_all": 0,              // Доставка
     *          "delivery_discount": 0,         // Знижка за доставку
     *          "delivery": 0,                  // Доставка остаточна (з врахуванням знижок)
     *          "order_discount": 0,            // Знижка додаткова на замовлення (акційна + кастомна)
     *          "difference_old_prices": 160,   // Різниця старої ціни товарів від поточної ціни позицій в корзині/замовленні (інформаційне значення!)
     *          "sum_old_prices": 600,          // Сума старих цін (інформаційне значення!)
     *          "total_discount": 0,            // Знижка сумарна (товари + доставка + замовлення)
     *          "total": 440                    // Сума остаточна (до оплати)
     *      },
     *      "form": {
     *          "payments": [
     *              {
     *                  "key": "wayforpay",
     *                  "name": "WayForPay",
     *                  "title": "Оплата WayForPay",
     *                  "desc": "Оплатити онлай через платіжну систему WayForPay"
     *              },
     *              {
     *                  "key": "liqpay",
     *                  "name": "LiqPay",
     *                  "title": "Оплата LiqPay",
     *                  "desc": "Оплатити онлай через платіжну систему LiqPay"
     *              },
     *              {
     *                  "key": "no",
     *                  "name": "При отриманні",
     *                  "title": "Оплата при отриманні",
     *                  "desc": "Оплатити при отримання замовлення у поштовому відділенні чи самовивозі"
     *              }
     *          ],
     *          "shippings": [
     *              {
     *                  "key": "novaposhta",
     *                  "name": "Відділення Нова Пошта",
     *                  "title": "Відділення Нова Пошта",
     *                  "desc": ""
     *              },
     *              {
     *                  "key": "novaposhta_locker",
     *                  "name": "Поштомат Нова Пошта",
     *                  "title": "Поштомат Нова Пошта",
     *                  "desc": ""
     *              },
     *              {
     *                  "key": "novaposhta_courier",
     *                  "name": "Кур'єр Нова Пошта",
     *                  "title": "Кур'єр Нова Пошта",
     *                  "desc": ""
     *              },
     *              {
     *                  "key": "ukrposhta",
     *                  "name": "Відділення Укрпошта",
     *                  "title": "Відділення Укрпошта",
     *                  "desc": ""
     *              },
     *              {
     *                  "key": "pickup",
     *                  "name": "Самовивіз",
     *                  "title": "Самовивіз",
     *                  "desc": ""
     *              }
     *          ]
     *      },
     *      "sblocks": [],
     *  }
     */
    public function checkoutForm(Request $request, Cart $cart)
    {
        $this->addProductsFromIds($request, $cart);

        $res = [
            'data' => null,
            'total' => null,
            'sblocks' => null,
        ];

        if (($order = $cart->order()) && $order->purchases()->count()) {
            $res = array_merge($res, $this->getCartData($order, $request->user()));
        }

        return $res;
    }

    /**
     * Масове додавання товарів в корзину.
     *
     * @param Request $request
     * @param Cart $cart
     * @return void
     */
    protected function addProductsFromIds(Request $request, Cart $cart): void
    {
        $variations = collect();

        if ($ids = filter_explode($request->ids)) {
            $variations = $variations->merge(ProductVariation::findMany($ids));
        }

        if ($skus = filter_explode($request->sku)) {
            $variations = $variations->merge(ProductVariation::whereIn('sku', $skus)->get());
        }

        foreach ($variations as $product) {
            $this->add($request, $product, $cart);
        }
    }

    /**
     * Дані корзини: товари, способи доставки, оплати, користувач, суми.
     *
     * @param Order $order
     * @return array
     */
    protected function getCartData(Order $order, User|null $user = null): array
    {
        $order->load([
            'purchases.variation.media',
            'purchases.variation.product.media',
            'purchases.variation.product.category',
            'purchases.variation.properties.attribute',
        ]);

        $res['data'] = [
            'id' => $order->id,
            'user' => $user?->only('id', 'name', 'lastname', 'middlename', 'phone', 'email') ?: null,
//                'recipient' => $order->getAdded('recipient', null),
            'shipping' => $order->getAdded('shipping') ?: $user?->getAdded('shipping') ?: null,
            'payment' => $order->getAdded('payment') ?: $user?->getAdded('payment', []) ?: null,
            //'discounts' => DiscountResource::collection($order->discounts), // застосовані промокоди
            'purchases' => PurchaseResource::collection($order->purchases),
        ];

        $res['total'] = $order->getTotalInfo();

        return $res;
    }

    /**
     * @api {post} /api/cart/{variation:id}/add 03. Додати variation в корзину
     * @apiVersion 1.0.0
     * @apiName PostCartsAdd
     * @apiGroup ShopCart
     *
     * @apiDescription Після додавання товару, в response буде cart_id, який треба зберегти на фронті та передавати Header (sCart) або в request (sCart)
     *
     * @apiHeader {String} sCart Cart ID: `b8bbd23a-44b4-4a0e-8641-75993175b3a2`
     *
     * @apiParam {Integer} [quantity=1] Кількість, яку додати
     *
     * @apiParamExample {json} Request-Example:
     *   {
     *       "quantity": 1
     *   }
     *
     * @apiSuccessExample {json} Response-Example: HTTP/1.1 200 OK
     *   {
     *       "message": "Добавлено в корзину",
     *       "cart_id": "612320e0-7719-47db-9fd7-12d79e6a89ac"
     *   }
     *
     * @apiSuccessExample {json} Response-Not-Available-Example: HTTP/1.1 200 OK
     *   {
     *       "status": "error",
     *       "message": "Товару немає в наявності",
     *       "cart_id": null
     *   }
     */
    public function add(Request $request, ProductVariation $variation, Cart $cart)
    {
        $request->validate([
            'quantity' => 'sometimes|integer|min:1',
        ]);

        if (empty($request->quantity)) {
            $request->merge(['quantity' => 1]);
        }
        $qty = $request->quantity;

        if ($variation->stock_qty < $qty ||
            $variation->countCart() >= $variation->stock_qty ||
            $variation->countCart() + $qty > $variation->stock_qty) {

            if ($variation->countCart()) {
                if ($variation->countCart() >= $variation->stock_qty) {
                    return response()->json([
                        'status' => 'success',
                        'message' => trans('client.You have all available products in your cart'),
                        'cart_id' => $cart->order()?->id,
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => trans('client.Product is not available, or choose a smaller quantity'),
                        'cart_id' => $cart->order()?->id,
                    ]);
                }
            }

            if ($variation->stock_qty) {
                return response()->json([
                    'status' => 'error',
                    'message' => trans('client.Product is not available, or choose a smaller quantity'),
                    'cart_id' => $cart->order()?->id,
                ]);
            }

            return response()->json([
                'status' => 'error',
                'message' => trans('client.Product is not available'),
                'cart_id' => $cart->order()?->id,
            ]);
        }

        $cart->add($variation, $request->only('quantity', 'source'));

        return response()->json([
            'message' => trans('client.Added to cart'),
            'cart_id' => $cart->order()->id,
        ]);
    }

    /**
     * @api {post} /api/cart/{purchase:id}/remove 04. Видалити purchase з корзини
     * @apiVersion 1.0.0
     * @apiName PostCartsRemove
     * @apiGroup ShopCart
     *
     * @apiParam {Integer} [quantity=0-всі] Кількість, яку видалити
     *
     * @apiParamExample {json} Request-Example:
     *  {
     *      "quantity": 1
     *  }
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *  {
     *      "message": "Видалено з корзини"
     *  }
     */
    public function remove(Request $request, Purchase $purchase, Cart $cart)
    {
        $request->validate([
            'quantity' => 'sometimes|integer|min:0',
        ]);

        $cart->remove($purchase, $request->only('quantity'));

        return response()
            ->json(['message' => trans('client.Removed from cart')]);
    }

    /**
     * TODO: validation counts
     *
     * @api {post} /api/cart/sync 07. Синхронізація даних
     * @apiVersion 1.0.0
     * @apiName CartSync
     * @apiGroup ShopCart
     *
     * @apiDescription Метод дозволяє додавати, видаляти, змінювати кількості елеменів.
     *
     * @apiParam {Array} [added] Масив ID-ів які будуть додані (variation.id)
     * @apiParam {Array} [removed] Масив ID-ів які будуть видаленні (purchase.id)
     * @apiParam {Array} [changed] Масив даних обєктів для зміни (purchase.id)
     *
     * @apiParamExample {json} Request-Example:
     *     {
     *          "added": [
     *              {
     *                  "id": "123",
     *                  "quantity": 1,
     *              },
     *              {
     *                  "id": "456",
     *                  "quantity": 2,
     *              }
     *          ],
     *          "removed": ["2345", "6789"],
     *          "changed": [
     *              {
     *                  "id": "6789",
     *                  "quantity": 5,
     *              },
     *              {
     *                  "id": "4321",
     *                  "quantity": 3,
     *              }
     *          ]
     *     }
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *    {
     *       "message": "Успішно змінено",
     *    }
     */
    public function sync(Request $request, Cart $cart)
    {
        $request->validate([
            'added' => 'sometimes|array',
            'removed' => 'sometimes|array',
            'changed.*.id' => 'required|string',
            'changed.*.quantity' => 'nullable|integer|min:0|max:10000',
        ]);

        $cart->sync($request->only('added', 'removed', 'changed'));

        return response()
            ->json(['message' => trans('client.Changed successful')]);
    }

    /**
     * @api {post} /api/cart/checkout 08. Оформити замовлення
     * @apiVersion 1.0.0
     * @apiName CartCheckout
     * @apiGroup ShopCart
     *
     * @apiHeader {String} [sCart] ID корзини, наприклад: `b8bbd23a-44b4-4a0e-8641-75993175b3a2`
     *
     * @apiDescription Якщо поле `destination` в response прийшло не порожнє - перенаправте клієнта на вказаний там URL (онлайн оплату).
     * Приклад форми: https://i.imgur.com/QirZS9R.png
     * Поля для НП (отримати в /suggests/...): https://i.imgur.com/NpKgel3.png
     *
     *
     * @apiParam {Array} [user] Дані користувача (тільки для не авторизованого, якщо потрібно його зареєструвати!).
     * @apiParam {Array} recipient Дані отримувача замовлення.
     * @apiParam {String=i,other} recipient.type=i Тип отримувача замовлення.
     * @apiParam {Array} shipping Дані доставки (поля в залежності від обраного способу доставки: https://i.imgur.com/A83HREJ.png).
     * @apiParam {String=novaposhta,novaposhta_locker,novaposhta_courier,ukrposhta,international,address,pickup} shipping.method Дані доставки.
     * @apiParam {String=no-при_отриманні,requisite,paycard,cash,fondy,liqpay,stripe,wayforpay,paypal,monobank} payment.gateway=no Спосіб оплати.
     *
     * @apiParamExample {json} Загальне:
     * {
     *     "user": {
     *          "email": "eva@app.com",
     *          "name": "Eva",
     *          "lastname": "Marley",
     *          "middlename": "Marley",
     *          "phone": "123456789"
     *     },
     *     "recipient": {
     *          "type": "other|i",
     *          "name": "Bob",
     *          "lastname": "Green",
     *          "middlename": "Marley",
     *          "phone": "123456780",
     *          "email": "bob@app.com",
     *          "callme": false
     *      },
     *     "shipping": {
     *        "method": "international",
     *        "international": {
     *              "country": "US",
     *              "region": "Virginia",
     *              "city": "Richmond",
     *              "street": "River 1",
     *              "address": "Richmonder",
     *              "apartment": "14",
     *              "zipcode": "12345",
     *        }
     *     },
     *     "client_comment": "Color red please!",
     *     "payment": {
     *        "gateway": "no,requisite,paycard,cash,fondy,liqpay,stripe,wayforpay,paypal,monobank"
     *     }
     * }
     *
     * @apiParamExample {json} NovaPost:
     *  {
     *      "shipping": {
     *         "method": "novaposhta",
     *         "novaposhta": {
     *               "CityRef": "db5c893b-391c-11dd-90d9-001a92567626",
     *               "CityName": "Луцьк",
     *               "WarehouseRef": "40498332-e1c2-11e3-8c4a-0050568002cf",
     *               "WarehouseName": "Відділення №11 (вул. Кривий Вал)",
     *         }
     *      }
     *  }
     *
     * @apiParamExample {json} NovaPost Locker:
     *  {
     *      "shipping": {
     *         "method": "novaposhta_locker",
     *         "novaposhta": {
     *               "CityRef": "db5c893b-391c-11dd-90d9-001a92567626",
     *               "CityName": "м. Луцьк, Волинська обл.",
     *               "WarehouseRef": "40498332-e1c2-11e3-8c4a-0050568002cf",
     *               "WarehouseName": "Поштомат "Нова Пошта" №5582: вул. Конякіна, 18а (маг. АТБ)",
     *         }
     *      }
     *  }
     *
     * @apiParamExample {json} NovaPost Courier:
     *   {
     *       "shipping": {
     *          "method": "novaposhta_courier",
     *          "novaposhta_courier": {
     *                "SettlementTypeCode": "м.",
     *                "RecipientArea": "Волинська",
     *                "RecipientAreaRegions": "Луцький",
     *                "RecipientCityName": "Луцьк",
     *                "RecipientAddressName": "Кривий Вал",
     *                "RecipientHouse": "6",
     *                "RecipientFlat": "5"
     *          }
     *       }
     *   }
     *
     * @apiParamExample {json} Ukrposhta:
     *    {
     *        "shipping": {
     *           "method": "ukrposhta",
     *           "ukrposhta": {
     *                 "region_id": "263",
     *                 "region": "Волинська",
     *                 "city_id": "3477",
     *                 "city": "Луцьк",
     *                 "warehouse": "Відділення №1",
     *                 "postcode": "43002",
     *           }
     *        }
     *    }
     *
     * @apiParamExample {json} Address:
     *     {
     *         "shipping": {
     *            "method": "address",
     *            "address": {
     *                  "region": "Волинська",
     *                  "city": "Луцьк",
     *                  "street": "Кривий Вал",
     *                  "house": "6",
     *                  "apartment": "5",
     *            }
     *         }
     *     }
     *
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *     {
     *       "message": "Замовлення збережено!"
     *       "order": {
     *          "id": "9bc1ff7a-c99d-4c0b-8f19-e9aeabbf4580",
     *          "status": "pending",
     *          "number": "1034"
     *       },
     *       "destination": "https://www.paypal.com/ua/webapps/mpp/home"
     *     }
     *
     */
    public function checkout(CartCheckoutRequest $request, Cart $cart)
    {
        if (($res = $cart->validateCheckout()) !== true) {
            return response()->json($res)
                ->setStatusCode(\Illuminate\Http\Response::HTTP_NOT_ACCEPTABLE);
        }

        $data = $request->getData();

        // Обираємо або Створюємо юзера
        if (!$request->user()) {

            if (is_string($request->input('user.email')) && $user = User::firstWhere('email', $request->input('user.email'))) {
                $data['user_id'] = $user->id;
            }

            elseif (is_string($request->input('user.phone')) && $user = User::firstWhere('phone', $request->input('user.phone'))) {
                $data['user_id'] = $user->id;
            }

            elseif (true) {
                $user = StoreUserAction::run([
                    'email' => $request->input('user.email'),
                    'name' => $request->input('user.name'),
                    'lastname' => $request->input('user.lastname'),
                    'middlename' => $request->input('user.middlename'),
                    'phone' => $request->input('user.phone'),
                    'source' => 'order',
                    '_send_email_verified' => 1,
                    '_send_created_notify' => 1,
                ]);
                $data['user_id'] = $user->id;
            }
        }

        // Спроба замовлення (натискає Оформити / Перейти на оплату)
        $data['preordered'] = 1;

        // Створювати відправлення
        $data['ordersending'] = 1;

        $order = $cart->checkout($data);

        $destination = '';
        $message = trans('alerts.cart.success');

        // Переходимо до оплати
        if ($gateway = $request->input('payment.gateway', 'no')) {
            $res = (new PaymentManager)->doPay($gateway, $order);

            // редірект на платіжну
            if ($res['status'] === 'success' && Arr::get($res, 'res.url')) {
                $destination = Arr::get($res, 'res.url');
            }

            // післяоплата...
            elseif ($res['status'] === 'success' && Arr::get($res, 'message')) {
                $message = Arr::get($res, 'message');
            }

            // помилка платіжної системи, оплати,...
            else {
                $message = Arr::get($res, 'message');
            }
        }

        return response()->json([
            'message' => $message,
            'order' => $order->only('id', 'number', 'status'),
            'destination' => $destination,
        ]);
    }

    /**
     * @api {get} /api/cart/order/{order:number} 09. Інформація нового замовлення
     * @apiVersion 1.0.0
     * @apiName CartsOrder
     * @apiGroup ShopCart
     *
     * @apiDescription Отримати публічну інформацію про нове замволення по його номеру, наприклад для сторінки "Дякуємо за оплату!"
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *  {
     *      "data": {
     *          "id": "32f99c1f-41c7-41f5-9403-6c215cea1a76",
     *          "number": "1234",
     *          "status": "pending",
     *          "created_at": "2025-06-09T13:53:50.000000Z",
     *          "ordered_at": "2025-06-09T13:53:57.000000Z",
     *          "status_data": {
     *              "key": "pending",
     *              "name": "Очікує",
     *              "color": "#C86407",
     *              "bg": "#FFE9CE"
     *          },
     *          "purchases": [
     *              {
     *                  "id": "f4ea1882-7935-49c4-85ad-cc8bd621e627",
     *                  "price": 450,
     *                  "quantity": 1,
     *                  "discount": 0,
     *                  "total": 450,
     *                  "variation": {...}
     *              }
     *          ],
     *          "total": {
     *              "quantity": 1,                  // Кількість всього товарів
     *              "quantity_unique": 1,           // Кількість унікальних одиниць товарів
     *              "purchases_all": 450,           // Сума за товари
     *              "purchases_discount": 0,        // Знижка за товари
     *              "purchases": 450,               // Сума за товари остаточна (з врахуванням знижок)
     *              "delivery_all": 0,              // Доставка
     *              "delivery_discount": 0,         // Знижка за доставку
     *              "delivery": 0,                  // Доставка остаточна (з врахуванням знижок)
     *              "order_discount": 0,            // Знижка додаткова на замовлення (акційна + кастомна)
     *              "difference_old_prices": 50,    // Різниця старої ціни товарів від поточної ціни позицій в корзині/замовленні (інформаційне значення!)
     *              "sum_old_prices": 500,          // Сума старих цін (інформаційне значення!)
     *              "total_discount": 0,            // Знижка сумарна (товари + доставка + замовлення)
     *              "total": 450                    // Сума остаточна (до оплати)
     *          }
     *      }
     *  }
     */
    public function order(string $orderNumber)
    {
        $order = Order::whereNumber($orderNumber)->whereNotNull('ordered_at')->latest('ordered_at')->firstOrFail();

        return OrderResource::make($order->load('purchases.variation.product.category'));
    }

    /**
     * @api {post} /api/cart/order/{order:id}/repeat 10. Повторити замовлення
     * @apiVersion 1.0.0
     * @apiName CartsRepeatOrder
     * @apiGroup ShopCart
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *  {
     *     "message": "Дані успішно оновлено!",
     *     "added": [
     *        {"id": "11ea1882-7935-49c4-85ad-cc8bd621e611", "name": "Яблука", "quantity": 3}
     *     ],
     *     "missed": [
     *        {"id": "22ea1882-7935-49c4-85ad-cc8bd621e622", "name": "Груші", "quantity": 2}
     *     ]
     *  }
     */
    public function repeat(Request $request, Order $order, Cart $cart)
    {
        $order = $order->load('purchases');
        $cart->init(true);
        $repeatedOrder = $cart->order();

        $originalPurchases = $order->purchases;
        $repeatedPurchases = $repeatedOrder->purchases()->get()->keyBy('model_id');

        $data = [
            'added' => [],
            'changed' => [],
        ];

        foreach ($originalPurchases as $purchase) {
            $variationId = $purchase->model_id;
            $quantity = $purchase->quantity;

            // Якщо такого товару немає в повторному замовленні — додаємо
            if (!$repeatedPurchases->has($variationId)) {
                $data['added'][] = [
                    'id' => $variationId,
                    'quantity' => $quantity,
                ];
            } else {
                $existingPurchase = $repeatedPurchases->get($variationId);

                // Якщо кількість менша — оновлюємо
                if ($existingPurchase->quantity < $quantity) {
                    $data['changed'][] = [
                        'id' => $existingPurchase->id,
                        'quantity' => $quantity,
                    ];
                }
            }
        }

        $sync = $cart->sync($data);

        return response()
            ->json([
                'message' => trans('client.Changed successful'),
                'cart_id' => $repeatedOrder->id,
                'added' => $sync['changelog']['added'],
                'missed' => $sync['changelog']['missed'],
            ]);
    }
}
