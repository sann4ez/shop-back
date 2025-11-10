<?php

namespace App\Http\Auth\Api\Controllers;

use App\Http\Auth\Api\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final class LoginController extends Controller
{
    protected string $loginVia = 'email';

    /**
     * LoginController constructor.
     */
    public function __construct()
    {
       $this->middleware('auth:sanctum', ['except' => ['login', 'refresh']]);
    }

    /**
     * @api {post} /api/login 02. Email: Логін
     * @apiVersion 1.0.0
     * @apiName AuthLogin
     * @apiGroup Authorisation
     *
     *
     * @apiDescription Отриманий токен авторизації додавати в Header кожного запиту: `Authorization: Bearer 123b24e5-d523-49fa-b76d-fce401b37ffb`
     *
     * @apiBody {String} email Email
     * @apiBody {String} password
     *
     *  @apiParamExample {json} Body-Example:
     *      {
     *          "email": "example@gmail.com",
     *          "password": "pass1234"
     *      }
     *
     *  @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     *  {
     *      "message": "Успішний вхід",
     *      "state": "active",
     *      "token": "9|7V9vOvocp9ACGLDPiT1aepnUsH2N5qJKTMxtTUvQ177b158e",
     *      "token_type": "bearer",
     *      "token_expires_in": null,
     *      "user": {
     *          "id": "2e265cc5-a6b1-4e8f-b3f2-810a9060885c",
     *          "name": "Олександр",
     *          "surname": null,
     *          "middlename": null,
     *          "email": "pikkugeltu@gufum.com",
     *          "phone": "380950058774",
     *          "role": "client",
     *          "status": "active"
     *      },
     *  }
     *
     */
    public function login(LoginRequest $request)
    {
        $this->loginVia = $request->email ? 'email' : 'phone';

        /** @var User $user */
        $user = User::where($this->conditions($request))->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                $this->loginVia => ['Вказані облікові дані не збігаються з нашими записами.'],
                'login' => ['Вказані облікові дані не збігаються з нашими записами.'],
            ]);
        }

        return response()->json([
            'message' => 'Успішний вхід',
            'state' => 'active',
        ] + $this->authResource($user));
    }

    /**
     * @api {post} /api/logout 03. Вихід
     * @apiVersion 1.0.0
     * @apiName AuthLogout
     * @apiGroup Authorisation
     *
     * @apiDescription Завершення сесії авторизованого користувача.
     * Bearer токен буде відкликано на сервері, тому його також потрібно видалити на клієнтській частині.
     *
     * @apiHeader {String} Authorization Токен доступу у форматі: `Bearer {token}`
     *
     * @apiHeaderExample {json} Header-Example:
     * {
     *   "Authorization": "Bearer 10|JPfB9huAMum5CarMreRUHa9tkA775Vp87M0zVmNna4651c72"
     * }
     *
     * @apiSuccessExample {json} Response-Success: HTTP/1.1 200 OK
     * {
     *   "message": "Успішний вихід",
     * }
     */
    public function logout(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Успішний вихід',
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    protected function conditions(Request $request): array
    {
        return [
            $this->loginVia => $request->{$this->loginVia},
            'status' => User::STATUS_ACTIVE,
        ];
    }
}
