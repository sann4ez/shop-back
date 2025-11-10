<?php

namespace App\Http\Client\Api\Controllers;

use App\Actions\UpdateProfile;
use App\Http\Client\Requests\ProfileUpdateRequest;
use App\Http\Client\Api\Resources\ProfileEditResource;
use App\Models\User;
use Illuminate\Http\Request;

final class ProfileController extends Controller
{
    /**
     * @api {get} /api/my/profile 01. Отримати дані профілю
     * @apiVersion 1.0.0
     * @apiName ProfileEdit
     * @apiGroup Profile
     *
     * @apiSuccessExample Success-Response:
     *  HTTP/1.1 200 OK
     *  "data": {
     *      "id": "9e888093-1ff1-428c-8cca-71169d7c1a3d",
     *      "name": "Олександр",
     *      "email": "ydiad@yandex.ua",
     *      "phone": "380950058766",
     *      "birthday": "2000-07-15T00:00:00.000000Z",
     *      "locale": "en",
     *      "avatar": {
     *          "id": "9e89fabe-6aee-4673-a4e7-31f0fa13a88c",
     *          "name": "article1",
     *          "url": "http://norm.test/storage/9e89fabe-6aee-4673-a4e7-31f0fa13a88c/article1.png",
     *          "gender": "",
     *          "conversions": {
     *              "thumb": {
     *                  "url": "http://norm.test/storage/9e89fabe-6aee-4673-a4e7-31f0fa13a88c/conversions/article1-thumb.jpg"
     *              }
     *          }
     *      }
     *  },
     *
     */
    public function edit(Request $request)
    {
        $user = $request->user();

        return ProfileEditResource::make($user->load('media'));
    }

    /**
     * @api {post} /api/my/profile 02. Оновити дані профілю
     * @apiVersion 1.0.0
     * @apiName ProfileUpdate
     * @apiGroup Profile
     *
     * @apiParam {String} [name] Ім'я
     * @apiParam {String} [email] Email
     * @apiParam {Object} [avatar] Avatar
     *
     * @apiParamExample {json} Request-Example:
     * {
     *     "name": "Bill",
     *     "email": "bill@app.com",
     *     "avatar": {"id": "8b8e2e64-d144-45f7-b5a3-dc3dff2bcd7f"}
     * }
     *
     */
    public function update(ProfileUpdateRequest $request)
    {
        /** @var User $user */
        $user = $request->user();
        UpdateProfile::run($user, $request->all());

        return response()->json(['message' => 'Дані успішно оновлено!']);
    }

    /**
     * @api {delete} /api/my/profile 03. Видалити профіль
     * @apiVersion 1.0.0
     * @apiName ProfileDelete
     * @apiGroup Profile
     *
     */
    public function delete(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $user->tokens()->delete();
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Дані успішно видалено!',
        ]);
    }
}
