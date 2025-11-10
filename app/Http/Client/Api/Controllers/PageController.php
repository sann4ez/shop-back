<?php

namespace App\Http\Client\Api\Controllers;

use App\Http\Client\Api\Resources\PageResource;
use App\Http\Client\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

final class PageController extends Controller
{
    /**
     *  @api {get} /api/pages/{slug} 01. Дані сторінки
     *  @apiVersion 1.0.0
     *  @apiName PageShow
     *  @apiGroup Pages
     *
     *  @apiDescription Повертає контент сторінки за вказаним slug.<br>
     *  Доступними можуть бути слаги: <code>home</code>, <code>personal</code>, <code>offer</code>, <code>policy</code>, <code>vacancies</code> або інші.
     *  <br>Повний список слагів доступний у розділі "Сторінки" в адмін-панелі.
     *
     *  @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *  {
     *      "data": {
     *          "id": "6c768024-8c48-452a-ad87-17aca452a849",
     *          "entity": "page",
     *          "slug": "home",
     *          "name": "Головна",
     *          "body": "<p>Онлайн-магазин кормів для хвостатих та вухатих</p>\r\n<p><strong>Шукаєте якісний корм для свого улюбленця?</strong></p>\r\n<p>У [Назва сайту] ви знайдете все необхідне для здоров'я та щастя ваших пухнастих друзів. Ми пропонуємо широкий асортимент харчування, аксесуарів та засобів догляду для котів і собак. Замовляйте онлайн та отримуйте швидку доставку по всій Україні. Регулярні акції та знижки допоможуть заощадити на улюблених товарах.</p>\r\n<p>Шукаєте якісний корм для свого улюбленця? У [Назва сайту] ви знайдете все необхідне для здоров'я та щастя ваших пухнастих друзів. Ми пропонуємо широкий асортимент харчування, аксесуарів та засобів догляду для котів і собак. Замовляйте онлайн та отримуйте швидку доставку по всій Україні. Регулярні акції та знижки допоможуть заощадити на улюблених товарах.</p>\r\n<p>Онлайн-магазин кормів для хвостатих та вухатих</p>\r\n<p><strong>Шукаєте якісний корм для свого улюбленця?</strong></p>\r\n<p>У [Назва сайту] ви знайдете все необхідне для здоров'я та щастя ваших пухнастих друзів. Ми пропонуємо широкий асортимент харчування, аксесуарів та засобів догляду для котів і собак. Замовляйте онлайн та отримуйте швидку доставку по всій Україні. Регулярні акції та знижки допоможуть заощадити на улюблених товарах.</p>\r\n<p>Шукаєте якісний корм для свого улюбленця? У [Назва сайту] ви знайдете все необхідне для здоров'я та щастя ваших пухнастих друзів. Ми пропонуємо широкий асортимент харчування, аксесуарів та засобів догляду для котів і собак. Замовляйте онлайн та отримуйте швидку доставку по всій Україні. Регулярні акції та знижки допоможуть заощадити на улюблених товарах.</p>\r\n<p> </p>",
     *          "template": "home"
     *      },
     *      "blocks": [
     *          {
     *              "id": 63,
     *              "name": "Верхній блок (головна)",
     *              "slug": "top",
     *              "type": "top",
     *              "data": {
     *                  "img": null,
     *                  "left": {
     *                      "title": "Мяв",
     *                  },
     *                  "right": {
     *                      "title": "Гав",
     *                  },
     *                  "title": "Корм, гідний корони UK",
     *                  "left_items": [
     *                      {
     *                          "img": "https://dropshop.demka.online/imagecache/68120defc6450.png",
     *                          "weight": "0"
     *                      },
     *                  ],
     *                  "right_items": [
     *                      {
     *                          "img": "https://dropshop.demka.online/imagecache/68120defd2636.png",
     *                          "weight": "0"
     *                      },
     *                  ]
     *              }
     *          },
     *      ],
     *  }
     */
    public function show(Request $request, string $slug)
    {
        /** @var Page $page */
        $page = Page::where('slug', $slug)->firstOrFail();

        $page->checkAllowed();

        return PageResource::make($page->checkAllowed())
            ->additional([
                'blocks' => $page->getResourceBlocks(),
            ]);
    }
}
