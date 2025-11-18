<?php

namespace App\Http\Client\Api\Controllers;

use App\Http\Client\Api\Resources\ProductCategoryListResource;
use App\Http\Client\Api\Resources\ProductCategoryShowResource;
use App\Http\Client\Api\Resources\ProductVariationShowResource;
use App\Http\Client\Api\Resources\ProductVariationListResource;
use App\Http\Client\Api\Resources\ProductVariationSwitchingResource;
use App\Http\Client\Api\Resources\SeoResource;
use App\Http\Client\Api\Resources\TermSimpleResource;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Term;
use App\VariationBuilder;
use Illuminate\Http\Request;

final class ShopController extends Controller
{
    /**
     *  @api {get} /api/shop/variations 01. Список варіацій
     *  @apiVersion 1.0.0
     *  @apiName CatalogIndex
     *  @apiGroup Shop
     *
     *  @apiDescription Отримання списку варіацій товарів з підтримкою фільтрації, сортування та пагінації.
     *
     *  @apiExample {curl} ExampleURL:
     *       /api/shop/variations?price_from=49&price_to=999999&facet[funktsiia][0]=dlia-travlennia&facet[funktsiia][1]=antyoksydant&facet[funktsiia][2]=dlial-shkiry-ta-shersti&facet[alerheny][0]=kuriatyna-1&facet[produkt][0]=funktsionalni-lasoshchi&facet[produkt][1]=boksy&per_page=12&page=1&category=dlia-sobak
     *
     *  @apiParam {Integer} [page] Номер сторінки (пагінація)
     *  @apiParam {Integer} [per_page] Кількість елементів на сторінці
     *  @apiParam {String=price,price_cost,rating,name,vname,income_at,created_at,default,random} [sort=income_at] Поле для сортування
     *  @apiParam {String=asc,desc} [order=asc] Напрямок сортування
     *  @apiParam {String} [q] Пошуковий запит (по назві, артикулу, GTIN тощо)
     *  @apiParam {String=1,0} [sort_reality=0] Показувати спочатку товари, що є в наявності
     *  @apiParam {String} [category] Слаг категорії (використовується для підстановки у `categories`)
     *  @apiParam {String} [categories] Слаги або ID категорій через кому або масив
     *  @apiParam {String} [brands] Слаги або ID брендів через кому або масив
     *  @apiParam {String} [tags] Слаги або ID тегів через кому або масив
     *  @apiParam {String} [markers] Ключі маркерів (через кому або масив)
     *  @apiParam {String} [ids] Список ID варіацій через кому
     *  @apiParam {String} [without] ID, які потрібно виключити (через кому)
     *  @apiParam {String} [sku] SKU для пошуку (через кому)
     *  @apiParam {Float} [price_from] Ціна, від
     *  @apiParam {Float} [price_to] Ціна, до
     *  @apiParam {Float} [price_cost_from] Мінімальна собівартість
     *  @apiParam {Float} [price_cost_to] Максимальна собівартість
     *  @apiParam {Integer} [stock_qty_from] Мінімальна кількість на складі
     *  @apiParam {Integer} [stock_qty_to] Максимальна кількість на складі
     *  @apiParam {String=normal,critical,zero,minus} [stock_qty] Тип запасу
     *  @apiParam {String=1,0} [in_stock] Наявність (1 — в наявності, 0 — нема)
     *  @apiParam {String=1,0} [has_discount] Мають знижку (ація, стара/нова ціна)
     *  @apiParam {String=1,0} [has_promotion] Мають акцію
     *  @apiParam {String=1,0} [promotion_id] По ID акції
     *  @apiParam {String=1,0} [has_parities] Чи має паритети
     *  @apiParam {String=1,0} [is_default] Чи є дефолтною варіацією
     *  @apiParam {String=main,attribute,parity,none,null} [groped_type] Тип групування варіацій
     *  @apiParam {Array} [facet] Фасетний фільтр по атрибутам товарів. Дивитись приклад ExampleURL
     *  @apiParam {String=slug,key} [_by=slug] Метод підстановки ID для категорій/брендів/тегів/маркерів (по слагу чи ключу)
     *  @apiParam {Integer} [limit] Обмеження кількості результатів (без пагінації)
     *
     *  @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *  {
     *      "data": [
     *          {
     *              "id": "79bd2e2b-e3d2-47be-913d-41a6a630cf18",
     *              "slug": "lasoshchi-dlia-khvostatoho-ta-3",
     *              "name": "Ласощі для хвостатого та вухатого улюбленця",
     *              "sku": "5000014",
     *              "status": "published",
     *              "multiplicity": 1,
     *              "stock_qty": 250,
     *              "min_qty": 1,
     *              "prices": {
     *                  "now": 49,
     *                  "old": 75,
     *                  "discount": 26,
     *                  "promotion": null,
     *                  "desc": "Product New/Old price",
     *                  "product": 49,
     *                  "currency": {
     *                      "code": "UAH",
     *                      "symbol": "₴"
     *                  },
     *                  "discount_percent": 35
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
     *                              "id": "ec2a2f23-0cb7-49d4-8abb-f800f6f1741c",
     *                              "slug": "500-h",
     *                              "color": null,
     *                              "image": "",
     *                              "value": "500 г"
     *                              },
     *                              "variation": {
     *                                  "id": "f4eee297-5c94-49e3-b0c6-df1bdd356ff5",
     *                                  "url": "#",
     *                                  "name": "Ласощі для хвостатого та вухатого улюбленця Ласощі для хвостатого  та вухатого улюбленця. 500 грам",
     *                                  "slug": "lasoshchi-dlia-khvostatoho-ta-1",
     *                                  "stock_qty": 120
     *                              },
     *                              "is_current": false
     *                          },
     *                      ]
     *                  }
     *              ],
     *              "states": {
     *                  "is_favorite": true,
     *                  "in_cart": false,
     *                  "count_cart": 0,
     *                  "in_comparison": false
     *              },
     *              "markers": [],
     *              "images": [
     *                  {
     *                      "id": "29e704ac-7a3d-422b-90dd-c58b9e664c64",
     *                      "name": "swiper-img-2",
     *                      "url": "https://dropshop.demka.online/storage/29e704ac-7a3d-422b-90dd-c58b9e664c64/swiper-img-2.webp",
     *                      "conversions": {
     *                          "big": {
     *                              "url": "https://dropshop.demka.online/storage/29e704ac-7a3d-422b-90dd-c58b9e664c64/conversions/swiper-img-2-big.webp"
     *                          },
     *                          "thumb": {
     *                              "url": "https://dropshop.demka.online/storage/29e704ac-7a3d-422b-90dd-c58b9e664c64/conversions/swiper-img-2-thumb.jpg"
     *                          },
     *                          "preview": {
     *                              "url": "https://dropshop.demka.online/storage/29e704ac-7a3d-422b-90dd-c58b9e664c64/conversions/swiper-img-2-preview.webp"
     *                          }
     *                      }
     *                  },
     *              ],
     *              "product": {
     *                  "id": "faebd621-07c5-4943-bf85-298ea6dcdeda",
     *                  "rating": 5,
     *                  "comments_count": 1,
     *                  "income_at": "2025-04-30T12:20:25.000000Z",
     *                  "productparity": null,
     *                  "category": {
     *                      "id": "d51506a4-5173-4699-bf4c-6b36553339b2",
     *                      "name": "Для собак",
     *                      "slug": "dlia-sobak",
     *                      "variations_count": 6
     *                  },
     *                  "brand": null,
     *                  "fields": {
     *                      "protein": "Яловичина"
     *                  }
     *              },
     *              "specification": [
     *                  {
     *                      "attribute": {
     *                          "id": "7f96f713-6d15-498e-b450-13d2100766b6",
     *                          "slug": "vaha",
     *                          "name": "Вага",
     *                          "format": "text"
     *                      },
     *                      "properties": [
     *                          {
     *                              "id": "ebfb3a7f-0d1c-41b4-a752-24ed04abd2db",
     *                              "slug": "50-h",
     *                              "value": "50 г",
     *                              "color": null,
     *                              "image": ""
     *                          }
     *                      ]
     *                  },
     *              ]
     *          }
     *      ],
     *      "links": {
     *          "first": "https://dropshop.demka.online/api/shop/variations?page=1",
     *          "last": "https://dropshop.demka.online/api/shop/variations?page=1",
     *          "prev": null,
     *          "next": null
     *      },
     *      "meta": {
     *          "current_page": 1,
     *          "from": 1,
     *          "last_page": 1,
     *          "links": [
     *              {
     *                  "url": null,
     *                  "label": "&laquo; Назад",
     *                  "active": false
     *              },
     *              {
     *                  "url": "https://dropshop.demka.online/api/shop/variations?page=1",
     *                  "label": "1",
     *                  "active": true
     *              },
     *              {
     *                  "url": null,
     *                  "label": "Далі &raquo;",
     *                  "active": false
     *              }
     *          ],
     *          "path": "https://dropshop.demka.online/api/shop/variations",
     *          "per_page": 12,
     *          "to": 1,
     *          "total": 1
     *      },
     *  }
     */
    public function variations(Request $request)
    {
        $variations = ProductVariation::query()
            ->with([
                'media.model',
                'product',
                'product.media.model',
                'product.category',
//                'product.productparity',
                'properties',
                'properties.media',
                'properties.attribute',
//                'product.brand',
//                'product.markers',
//                'promotions',
            ])
            ->byGropedType($request->groped_type)
            ->filterable(
                $request->all(),
                [
                    'status' => ProductVariation::STATUS_PUBLISHED,
                    'sort_reality' => 1,
                ]
            )
            ->paginate();

        return ProductVariationListResource::collection($variations);
    }


    /**
     *  @api {get} /api/shop/variations/{variation:slug} 02. Одна варіація
     *  @apiVersion 1.0.0
     *  @apiName CatalogVariation
     *  @apiGroup Shop
     *
     *  @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *  {
     *      "data": {
     *          "id": "79bd2e2b-e3d2-47be-913d-41a6a630cf18",
     *          "entity": "variation",
     *          "name": "Ласощі для хвостатого та вухатого улюбленця Ласощі для хвостатого та вухатого улюбленця. 50 грам",
     *          "slug": "lasoshchi-dlia-khvostatoho-ta-3",
     *          "sku": "5000014",
     *          "status": "published",
     *          "multiplicity": 1,
     *          "stock_qty": 250,
     *          "min_qty": 1,
     *          "prices": {
     *              "now": 49,
     *              "old": 75,
     *              "discount": 26,
     *              "promotion": null,
     *              "desc": "Product New/Old price",
     *              "product": 49,
     *              "currency": [],
     *              "discount_percent": 35
     *          },
     *          "states": {
     *              "is_favorite": true,
     *              "in_cart": false,
     *              "count_cart": 0,
     *              "in_comparison": false
     *          },
     *          "fields": [],
     *          "markers": [],
     *          "images": [
     *              {
     *                  "id": "29e704ac-7a3d-422b-90dd-c58b9e664c64",
     *                  "name": "swiper-img-2",
     *                  "url": "https://dropshop.demka.online/storage/29e704ac-7a3d-422b-90dd-c58b9e664c64/swiper-img-2.webp",
     *                  "conversions": { "big": {"url": "..."}, "thumb": {"url": "..."}, "preview": {"url": "..."},}
     *              },
     *          ],
     *          "product": {
     *              "id": "faebd621-07c5-4943-bf85-298ea6dcdeda",
     *              "body": "<p>Цей корм ви знайдете все необхідне для здоров'я та щастя ваших пухнастих друзів. Ми пропонуємо широкий асортимент харчування, аксесуарів та засобів догляду для котів і собак. Замовляйте онлайн та отримуйте швидку доставку по всій Україні. Регулярні акції та знижки допоможуть заощадити на улюблених товарах.</p>",
     *              "rating": 5,
     *              "comments_count": 1,
     *              "income_at": "2025-04-30T12:20:25.000000Z",
     *              "brand": null,
     *              "productmodel": null,
     *              "productparity": null,
     *              "category": {
     *                  "id": "d51506a4-5173-4699-bf4c-6b36553339b2",
     *                  "name": "Для собак",
     *                  "slug": "dlia-sobak",
     *                  "variations_count": 6
     *              },
     *              "tags": [],
     *              "fields": {
     *                  "protein": "Яловичина",
     *                  "contents": [
     *                      {
     *                          "attr": "Сирий протеїн",
     *                          "prop": "0.37%",
     *                          "weight": "0"
     *                      },
     *                  ]
     *              }
     *          },
     *          "specification": [
     *              {
     *                  "attribute": {
     *                      "slug": "vaha",
     *                      "name": "Вага",
     *                      "format": "text"
     *                  },
     *                  "property": {
     *                      "slug": "50-h",
     *                      "value": "50 г",
     *                      "color": null,
     *                      "image": null
     *                  }
     *              },
     *          ]
     *      },
     *      "seo": {
     *          "metatags": {
     *              "title": " - DropShop",
     *              "robots": "noindex, nofollow",
     *              "og_site_name": "DropShop",
     *              "og_locale": "uk",
     *              "og_title": " - DropShop",
     *              "og_type": "page",
     *              "twitter_title": " - DropShop"
     *          },
     *          "h1": "",
     *          "text": "",
     *          "faq": []
     *      },
     *      "crumbs": [
     *          {
     *              "id": "d51506a4-5173-4699-bf4c-6b36553339b2",
     *              "slug": "dlia-sobak",
     *              "name": "Для собак",
     *              "model": "term"
     *          },
     *          {
     *              "id": "79bd2e2b-e3d2-47be-913d-41a6a630cf18",
     *              "slug": "lasoshchi-dlia-khvostatoho-ta-3",
     *              "name": "Ласощі для хвостатого та вухатого улюбленця. 50 грам",
     *              "model": "variation"
     *          }
     *      ],
     *      "sblocks": [],
     *      "switching": [
     *          {
     *              "attribute": {
     *                  "id": "7f96f713-6d15-498e-b450-13d2100766b6",
     *                  "slug": "vaha",
     *                  "name": "Вага",
     *                  "has_image": false,
     *                  "format": "text"
     *              },
     *              "properties": [
     *                  {
     *                      "property": {
     *                          "id": "ec2a2f23-0cb7-49d4-8abb-f800f6f1741c",
     *                          "slug": "500-h",
     *                          "value": "500 г",
     *                          "color": null,
     *                          "image": ""
     *                      },
     *                      "variation": {
     *                          "id": "f4eee297-5c94-49e3-b0c6-df1bdd356ff5",
     *                          "name": "Ласощі для хвостатого та вухатого улюбленця Ласощі для хвостатого  та вухатого улюбленця. 500 грам",
     *                          "url": "/lasoshchi-dlia-khvostatoho-ta-1",
     *                          "slug": "lasoshchi-dlia-khvostatoho-ta-1",
     *                          "stock_qty": 120
     *                      },
     *                      "is_current": false
     *                  },
     *              ]
     *          }
     *      ],
     *      "variations": [
     *          {
     *              "id": "76494577-bb15-4d97-8c0a-d1e2f46558bd",
     *              "name": "Ласощі для хвостатого та вухатого улюбленця. 100 грам",
     *              "slug": "lasoshchi-dlia-khvostatoho-ta-2",
     *              "sku": "5000013",
     *              "prices": {
     *                  "now": 80,
     *                  "old": 150,
     *                  "discount": 70,
     *                  "promotion": null,
     *                  "desc": "Product New/Old price",
     *                  "product": 80,
     *                  "currency": {
     *                      "code": "UAH",
     *                      "symbol": "₴"
     *                  },
     *                  "discount_percent": 47
     *              },
     *              "is_current": false,
     *              "image": {
     *                  "id": "edbefab9-17f4-43b3-85b2-db08342895e5",
     *                  "name": "swiper-img-2",
     *                  "url": "https://dropshop.demka.online/storage/edbefab9-17f4-43b3-85b2-db08342895e5/swiper-img-2.webp",
     *                  "conversions": { "big": {"url": "..."}, "thumb": {"url": "..."}, "preview": {"url": "..."},}
     *              }
     *          },
     *      ],
     *      "relateds": [],
     *      "parities": [],
     *      "recommends": [
     *          {
     *              "id": "117e1bd8-0cf4-4671-b2eb-441ee069f833",
     *              "slug": "lasoshchi-dlia-psa-500-hram",
     *              "name": "Ласощі для пса",
     *              "sku": "5000015",
     *              "status": "published",
     *              "multiplicity": 1,
     *              "stock_qty": 45,
     *              "min_qty": 1,
     *              "prices": {
     *                  "now": 135,
     *                  "old": 150,
     *                  "discount": 15,
     *                  "promotion": null,
     *                  "desc": "Product New/Old price",
     *                  "product": 135,
     *                  "currency": {
     *                      "code": "UAH",
     *                      "symbol": "₴"
     *                  },
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
     *                                  "id": "817c8208-4774-41d9-9d22-f8b6788801d4",
     *                                  "url": "#",
     *                                  "name": "Ласощі для пса Ласощі для пса. 50 грам",
     *                                  "slug": "lasoshchi-dlia-psa-50-hram",
     *                                  "stock_qty": 120
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
     *                      "id": "476b7edd-05ee-4f5e-ac94-c3b8a70406cb",
     *                      "name": "image-removebg (3) 4",
     *                      "url": "https://dropshop.demka.online/storage/476b7edd-05ee-4f5e-ac94-c3b8a70406cb/image-removebg-3-4.png",
     *                      "conversions": { "big": {"url": "..."}, "thumb": {"url": "..."}, "preview": {"url": "..."},}
     *                  },
     *              ],
     *              "product": {
     *                  "id": "6d863405-6c3c-43a2-a2f3-6cc21d144e50",
     *                  "rating": 3.67,
     *                  "comments_count": 3,
     *                  "income_at": "2025-04-29T11:45:20.000000Z",
     *                  "category": {
     *                      "id": "d51506a4-5173-4699-bf4c-6b36553339b2",
     *                      "name": "Для собак",
     *                      "slug": "dlia-sobak",
     *                      "variations_count": 6
     *                  },
     *                  "fields": {
     *                      "protein": null
     *                  }
     *              }
     *          },
     *      ],
     *  }
     *
     */
    public function variation(Request $request, ProductVariation $variation)
    {
        /** @var ProductVariation $variation */
        $variation = ProductVariation::whereSlug($variation->slug)
            ->byAllowed()
            ->with([
                'media',
                'product.media',
                'properties.media',
                'properties.attribute',
            ])
            ->firstOrFail();

        $additional = [
            'crumbs' => $variation->getBreadcrumbs(),
        ];

        return ProductVariationShowResource::make($variation)
            ->additional($additional);
    }

    /**
     *  @api {get} /api/shop/categories 03. Список категорій
     *  @apiVersion 1.0.0
     *  @apiName CatalogCategories
     *  @apiGroup Shop
     *
     *  @apiParam {string} [parent_id]
     *      `null` - всі категорії,<br>
     *      `0` - категорії першого рівня (без parent_id),<br>
     *      `id` - підкатегорії з parent_id
     *
     *  @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *  {
     *      "data": [
     *          {
     *              "id": "7d5c2d6d-4d28-4c95-b519-f76a9d59c763",
     *              "slug": "dlia-kotiv",
     *              "name": "Для котів",
     *              "icon": null,
     *              "image": null,
     *              "logo": null
     *          },
     *          {
     *              "id": "d51506a4-5173-4699-bf4c-6b36553339b2",
     *              "slug": "dlia-sobak",
     *              "name": "Для собак",
     *              "icon": null,
     *              "image": null,
     *              "logo": null
     *          }
     *      ],
     *  }
     */
    public function categories(Request $request)
    {
        $term = Term::whereVocabulary(Term::VOCABULARY_PRODUCT_CATEGORIES)->with('media')->filterable()->get();

        return ProductCategoryListResource::collection($term);
    }

    /**
     *  @api {get} /api/shop/categories/tree/view 04. Дерево категорій
     *  @apiVersion 1.0.0
     *  @apiName ShopCategoryTree
     *  @apiGroup Shop
     *
     *  @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *  {
     *      "data": [
     *          {
     *              "id": "47439c16-0912-48fd-ae10-2e97e3f2a1de",
     *              "slug": "dlia-kotiv",
     *              "name": "Для котів",
     *              "icon": null,
     *              "image": null,
     *              "logo": null,
     *              "children": [
     *                  {
     *                      "id": "4ee7a909-a132-4dec-b352-a74caa554a10",
     *                      "slug": "dlia-sobak",
     *                      "name": "Для собак",
     *                      "icon": null,
     *                      "image": null,
     *                      "logo": null,
     *                      "children": []
     *                  }
     *              ]
     *          }
     *      ],
     *  }
     *
     */
    public function categoriesTree(Request $request)
    {
        $categories = Term::byVocabulary(Term::VOCABULARY_PRODUCT_CATEGORIES)
            ->with('media')
            ->get()->toTree();

        return ProductCategoryListResource::collection($categories);
    }

    /**
     *  @api {get} /api/shop/categories/{category:slug} 05. Одна категорія
     *  @apiVersion 1.0.0
     *  @apiName ShopCategory
     *  @apiGroup Shop
     *
     *  @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *  {
     *      "data": {
     *          "id": "47439c16-0912-48fd-ae10-2e97e3f2a1de",
     *          "entity": "product_categories",
     *          "slug": "dlia-kotiv",
     *          "name": "Для котів",
     *          "body": null,
     *          "icon": null,
     *          "image": null,
     *          "logo": null,
     *          "filter": {
     *              "groped_type": null
     *          }
     *      },
     *      "seo": {
     *          "metatags": {
     *              "title": " - Dropshop",
     *              "robots": "index, follow",
     *              "og_site_name": "Dropshop",
     *              "og_locale": "uk",
     *              "og_title": " - Dropshop",
     *              "og_type": "page",
     *              "twitter_title": " - Dropshop"
     *          },
     *          "h1": "",
     *          "text": "",
     *          "faq": []
     *      },
     *      "crumbs": [],
     *      "sblocks": []
     *  }
     */
    public function category(Request $request, Term $category)
    {
        $category->checkAllowed()->load('media', 'ancestors');

        return \App\Http\Client\Api\Resources\ProductCategoryShowResource::make($category);
    }

    /**
     * @api {get} /api/shop/variations/facet 08. Фасетний фільтр для варіацій
     * @apiVersion 1.0.0
     * @apiName ShopFacet
     * @apiGroup Shop
     *
     * @apiDescription Отримати атрибути-значення для побудови facet filter `https://i.imgur.com/hfEdbF5.png` <br> Також має всі фільтри, як і на роуті <a href="#api-Shop-CatalogIndex">Shop | 01. Список варіацій</a>
     *
     * @apiParam {String} [category] Слаг категорії
     * @apiParam {String} [q] Пошуковий рядок
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *  {
     *      "categories": [
     *          {
     *              "id": "44681c9c-d405-4b1f-965e-e315798b4e3f",
     *              "name": "Іграшки",
     *              "slug": "ihrashky",
     *              "variations_count": 48,
     *              "children": [
     *                  {
     *                      "id": "ff5c3ff3-d390-4554-9685-8733ad8a0cec",
     *                      "slug": "ihrashky-lego",
     *                      "name": "Іграшки Lego",
     *                      "children": []
     *                  }
     *              ]
     *          },
     *          ... // Ще категорії
     *      ],
     *      "brands": [
     *          {
     *              "id": "27351b9d-4b55-411c-92cb-d9a5186e3374",
     *              "name": "Lego",
     *              "slug": "lego",
     *              "variations_count": 0,
     *              "is_allowed": true
     *          },
     *          ... // Ще бренди
     *      ],
     *      "markers": [
     *          {
     *              "id": "9ee46a25-c115-4176-a6c9-97731004a93a",
     *              "key": "top",
     *              "name": "TOP",
     *              "color": "#FB1010",
     *              "bg": "#000000",
     *              "is_allowed": false
     *          },
     *          ... // Ще маркери
     *      ],
     *      "attributes": [
     *          {
     *              "id": "1b4e978f-4390-4494-ace4-c46910617ae9",
     *              "slug": "color",
     *              "name": "Колір",
     *              "format": "image",
     *              "is_allowed": false,
     *              "properties": [
     *                  {
     *                      "id": "80470fd7-c604-4546-b76b-42ed656f6683",
     *                      "slug": "beige",
     *                      "value": "Beige",
     *                      "color": null,
     *                      "image": "https://dropshop.demka.online/storage/6fbbcdef-4152-47d3-9063-601f476d01d3/2023-10-16-17-23.png",
     *                      "is_allowed": false
     *                  },
     *                  ... // Ще properties
     *              ]
     *          },
     *          ... // Ще attributes
     *      ],
     *      "prices": {
     *          "min": 1,
     *          "max": 12999,
     *          "category": {
     *              "from": 8,
     *              "to": 1096
     *          },
     *          "search": {
     *              "from": 500,
     *              "to": 1000
     *          }
     *      },
     *      "info": {
     *          "in_stock_variations_count": 22
     *      }
     *  }
     */
    public function facet(Request $request)
    {
        return (new \App\Support\Shop\Filter())->facet($request->all());
    }
}
