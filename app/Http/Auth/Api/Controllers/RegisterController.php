<?php

namespace App\Http\Auth\Api\Controllers;

use App\Actions\StoreUserAction;
use App\Http\Auth\Api\Requests\RegisterRequest;
use App\Models\User;

final class RegisterController extends Controller
{
    /**
     *  @api {post} /api/register 01. Email: Реєстрація
     *  @apiVersion 1.0.0
     *  @apiName PostRegister
     *  @apiGroup Authorisation
     *
     *  @apiDescription Реєстрація нового користувача за допомогою Email.
     *
     *  @apiBody {String} name Ім’я
     *  @apiBody {String} [lastname] Прізвище
     *  @apiBody {String} [middlename] По батькові
     *  @apiBody {String} email Email-адреса
     *  @apiBody {String} phone Номер телефону
     *  @apiBody {Date} [birthday] Дата народження (YYYY-MM-DD)
     *  @apiBody {String} password Пароль
     *  @apiBody {String} [password_confirmation] Підтвердження пароля
     *  @apiBody {Boolean=0,1} [accept] Чи прийнято умови використання
     *
     *  @apiParamExample {json} Body-Example:
     *  {
     *      "name": "Іван",
     *      "lastname": "Іванов",
     *      "middlename": "Іванович",
     *      "email": "ivan@example.com",
     *      "phone": "+380501234567",
     *      "birthday": "1990-01-01",
     *      "password": "secret123",
     *      "password_confirmation": "secret123",
     *      "accept": 1
     *  }
     */
    public function register(RegisterRequest $request)
    {
        /** @var User $user */
        $user = StoreUserAction::run($request->validated());

        return response()->json([
            'message' => 'Вітаємо! Реєстрація успішна',
            'state' => 'active',
        ] + $this->authResource($user));
    }
}
