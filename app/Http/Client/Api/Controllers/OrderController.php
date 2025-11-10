<?php

namespace App\Http\Client\Api\Controllers;

use App\Http\Client\Api\Resources\OrderResource;
use App\Models\Auth\User;
use App\Models\Item;
use App\Models\Shop\Order;
use Illuminate\Http\Request;

final class OrderController
{
    /**
     *  @api {get} /api/my/orders 05. Список замовлень
     *  @apiVersion 1.0.0
     *  @apiName ProfileOrders
     *  @apiGroup Profile
     *
     *  @apiParam {Integer} [page] Номер сторінки
     *  @apiParam {Integer} [per_page] Кількість на сторінці
     *
     *  @apiSuccessExample {json} Response-Success: HTTP/1.1 202 Accepted
     *  {
     *  "data": [
     *      {
     *          "id": "32f99c1f-41c7-41f5-9403-6c215cea1a76",
     *          "number": "1021",
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
     *              "id": "f4ea1882-7935-49c4-85ad-cc8bd621e627",
     *              "price": 450,
     *              "quantity": 1,
     *              "discount": 0,
     *              "total": 450,
     *              "variation": {
     *              "id": "b9b46653-edbe-423a-aed2-dae2955dc3f5",
     *              "slug": "lasoshchi-dlia-khvostatoho-500",
     *              "name": "Ласощі для вухатого",
     *              "sku": "5000019",
     *              "status": "published",
     *              "multiplicity": 1,
     *              "stock_qty": 100,
     *              "min_qty": 1,
     *              "prices": {
     *                  "now": 450,
     *                  "old": 500,
     *                  "discount": 50,
     *                  "promotion": null,
     *                  "desc": "Product New/Old price",
     *                  "product": 450,
     *                  "currency": [],
     *                  "discount_percent": 10
     *              },
     *              "switching": [
     *                  {
     *                      "attribute": {
     *                          "id": "7f96f713-6d15-498e-b450-13d2100766b6",
     *                          "name": "Вага",
     *                          "slug": "vaha",
     *                          "format": "text",
     *                          "has_image": false
     *                      },
     *                      "properties": [
     *                          {
     *                              "property": {
     *                                  "id": "ec2a2f23-0cb7-49d4-8abb-f800f6f1741c",
     *                                  "slug": "500-h",
     *                                  "color": null,
     *                                  "image": "",
     *                                  "value": "500 г"
     *                              },
     *                              "variation": {
     *                                  "id": "ebaf088d-c231-4c50-a602-a2c6406df6c9",
     *                                  "url": "#",
     *                                  "name": "Ласощі для вухатого Ласощі для вухатого 50г",
     *                                  "slug": "lasoshchi-dlia-vukhatoho-50h",
     *                                  "stock_qty": 0
     *                              },
     *                              "is_current": true
     *                          },
     *                      ]
     *                  }
     *              ],
     *              "states": {
     *                  "is_favorite": false,
     *                  "in_cart": false,
     *                  "count_cart": 0,
     *                  "in_comparison": false
     *              },
     *              "markers": [],
     *              "images": [
     *                  {
     *                      "id": "9bc01a3d-886e-4f30-8180-1bb7e8d0ac5c",
     *                      "name": "image (1)",
     *                      "url": "https://dropshop.demka.online/storage/9bc01a3d-886e-4f30-8180-1bb7e8d0ac5c/image-1.png",
     *                      "conversions": {
     *                          "big": {
     *                              "url": "https://dropshop.demka.online/storage/9bc01a3d-886e-4f30-8180-1bb7e8d0ac5c/conversions/image-1-big.webp"
     *                          },
     *                          "thumb": {
     *                              "url": "https://dropshop.demka.online/storage/9bc01a3d-886e-4f30-8180-1bb7e8d0ac5c/conversions/image-1-thumb.jpg"
     *                          },
     *                          "preview": {
     *                              "url": "https://dropshop.demka.online/storage/9bc01a3d-886e-4f30-8180-1bb7e8d0ac5c/conversions/image-1-preview.webp"
     *                          }
     *                      }
     *                  }
     *              ],
     *              "product": {
     *                  "id": "1b7dc8f4-3415-43a0-af4a-a978c5f14bfb",
     *                  "rating": 4.5,
     *                  "comments_count": 4,
     *                  "income_at": "2025-04-30T12:44:24.000000Z",
     *                  "category": {
     *                      "id": "7d5c2d6d-4d28-4c95-b519-f76a9d59c763",
     *                      "name": "Для котів",
     *                      "slug": "dlia-kotiv",
     *                      "variations_count": 10
     *                  },
     *                  "fields": {
     *                   "protein": "Яловичина"
     *                  }
     *              },
     *              "specification": [
     *                  {
     *                      "attribute": {
     *                          "slug": "vaha",
     *                          "name": "Вага",
     *                          "format": "text"
     *                      },
     *                      "property": {
     *                          "slug": "500-h",
     *                          "value": "500 г",
     *                          "color": null,
     *                          "image": null
     *                      }
     *                  },
     *              ]
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
     *      },
     *  }
     *
     */
    public function index(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $orders = $user->orders()->latest('ordered_at')->withTrans([
                'purchases.variation.translations', 'purchases.variation.product.translations', 'purchases.variation.media', 'purchases.variation.product.category',
                'purchases.variation.properties.attribute', 'purchases.variation.properties.translations', 'purchases.variation.properties.attribute.translations',
            ])->where('type', Order::TYPE_ORDER)->paginate();

        return OrderResource::collection($orders);
    }

    /**
     *  @api {post} /api/my/orders/{order:number}/canceled 06. Скасувати замовлення
     *  @apiVersion 1.0.0
     *  @apiName ProfileOrderCanceled
     *  @apiGroup Profile
     *
     *  @apiDescription Скасувати можна тільки нове (`perform = pending`) замовлення, яке не підтверджено до виконання, і було зроблено не пізніше ніж 24 години тому.
     *
     *  @apiHeader {String} Authorization Bearer токен: `Bearer {token}`
     *
     *  @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *  {
     *      "message": "Операцію виконано успішно"
     *  }
     */
    public function canceled(Request $request, Order $order)
    {
        if ($order->user_id !== $request->user()->id) {
            abort(404);
        }

        if ($order->perform !== Order::PERFORM_PENDING) {
            return response()
                ->json(['message' => trans('client.Illegal operation')]);
        }

        if ($order->ordered_at->lt(now()->subDay())) {
            return response()
                ->json(['message' => trans('client.Illegal operation')]);
        }

        $order->setAttribute('perform', Order::PERFORM_CANCELLED);
        $order->setAttribute('status', Item::getSettingsValue(Item::TYPE_ORDER_STATUS, Item::SETTINGS_CLIENT_ORDER_CANCELED, 'canceled'));
        $order->save();

        return response()
            ->json(['message' => trans('client.Operation success')]);
    }

    /**
     * TODO
     * @apiPrivate
     * @api {post} /api/my/orders/{order:number}/payment 11. Отримати посилання на оплату
     * @apiVersion 1.0.0
     * @apiName ProfileOrderCanceled
     * @apiGroup Profile
     *
     * @apiDescription Скасувати можна тільки нове і не підтверджене до виконання замовлення!
     *
     */
    public function paymentLink(Request $request, Order $order)
    {
        //$request->gateway;

        // TODO

        return response()->json([
            'message' => 'Посилання на оплату отримано',
            'order' => $order->only('id', 'number', 'status'),
            'destination' => 'https://payment.todo',
        ]);
    }

}
