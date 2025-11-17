<?php

namespace App\Http\Client\Requests;

use App\Http\FormRequest;
use App\Models\Payment;
use App\Rules\Phone;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

final class CartCheckoutRequest extends FormRequest
{
    public function rules()
    {
        $res = [
            'user.name' => 'sometimes|string|max:50',
            'user.lastname' => 'sometimes|required|string|max:50',
            'user.middlename' => 'nullable|string|max:50',
            'user.phone' => ['sometimes', 'required', new Phone()],
            'user.email' => 'sometimes|email',

            'payment.gateway' => ['sometimes', 'required', Rule::in(array_merge(Payment::gatewaysList('key'), ['no']))],

            'shipping.country' => 'required|string',

            'client_comment' => 'nullable|string|max:2048',
        ];

        if (empty($this->recipient) && empty($this->user)) {
            $res['recipient'] = 'required';
        }

        // Валідація способів доставки
        $res = $this->rulesShipping($res);

        // ОТРИМУВАЧ
        $res = array_merge($res , [
            'recipient.type' => 'sometimes|in:i,other',
            'recipient.name' => 'sometimes|string|max:50',
            'recipient.lastname' => 'sometimes|string|max:50',
            'recipient.middlename' => 'nullable|string|max:50',
            'recipient.phone' => ['sometimes', 'required', new Phone()],
            'recipient.email' => 'sometimes|email:strict',
            'recipient.callme' => 'nullable|boolean',
        ]);

        return $res;
    }

    protected function prepareForValidation(): void
    {
        $requestData = [];

        // ПОШТОВА СЛУЖБА, АДРЕСА
        $method = $this->input('shipping.method');
        $shipping['method'] = $method;
        $shipping['country'] = $this->input("shipping.{$method}.country") ?: $this->input('shipping.country') ?: 'UA';

        // для сумісності з старішими версіями і виводу в адмінці:
        $shipping['region'] = $this->input("shipping.{$method}.region") ?: $this->input('shipping.region');
        $shipping['city'] = $this->input("shipping.{$method}.city") ?: $this->input("shipping.{$method}.CityName") ?: $this->input('shipping.city');
        $shipping['street'] = $this->input("shipping.{$method}.street") ?: $this->input('shipping.street');
        $shipping['house'] = $this->input("shipping.{$method}.house") ?: $this->input('shipping.house');
        $shipping['apartment'] = $this->input("shipping.{$method}.apartment") ?: $this->input('shipping.apartment');
        $shipping['zipcode'] = $this->input("shipping.{$method}.zipcode") ?: $this->input("shipping.{$method}.postcode") ?: $this->input('shipping.zipcode');
        $shipping['warehouse'] = $this->input("shipping.{$method}.warehouse") ?: $this->input("shipping.{$method}.WarehouseName") ?: $this->input('shipping.warehouse');
        $shipping['custom'] = $this->input("shipping.custom") ?: $this->input("shipping.{$method}.custom"); // TODO deprecated?

        $shipping['total'] = $this->input('shipping.address') ?: (implode('; ', array_filter(Arr::except($shipping, ['method']))));

        $shipping = array_merge(array_filter($this->input('shipping') ?: []), array_filter($shipping));
        $requestData['shipping'] = $shipping;


        // ОПЛАТА
        $payment = array_merge(['gateway' => 'no'], array_filter($this->input('payment') ?: []));
        if ($payment['gateway'] === 'received') { // TODO: for supported old (blade homemama, plante)
            $payment['gateway'] = 'no';
        }
        $requestData['payment'] = $payment;



        // КОРИСТУВАЧ
        $userData = [];
        if ($this->has('user') && is_array($this->user)) {
            $userData = Arr::only($this->user, [
                'name',
                'lastname',
                'middlename',
                'email',
                'phone',
            ]);

            if ($phone = Arr::get($userData, 'phone')) {
                $userData['phone'] = $this->getClearPhoneValue($phone);
            }
        } elseif (Auth::check()) {
            $userData = array_filter(Auth::user()->only('name', 'lastname', 'middlename', 'email', 'phone'));
        }

        $userData = array_merge([
            'name' => null,
            'lastname' => null,
            'middlename' => null,
            'email' => null,
            'phone' => null,
        ], $userData);

        $requestData['user'] = $userData;

        $recipientData = [];
        // ОТРИМУВАЧ
        if ($this->has('recipient') && is_array($this->recipient)) {
            $keys = ['name', 'lastname', 'middlename', 'email', 'phone', 'type', 'callme'];

            $recipientData = collect($keys)
                ->mapWithKeys(fn($key) => [$key => $this->recipient[$key] ?? null])
                ->toArray();
            $recipientData['type'] = $this->input('recipient.type') ?: 'i';
        }

        if ($phone = Arr::get($recipientData, 'phone')) {
            $recipientData['phone'] = $this->getClearPhoneValue($phone);;
        }

        $requestData['recipient'] = $recipientData;

        if (empty($requestData['user'])) {
            $requestData['user'] = Arr::only($requestData['recipient'] ?? [], ['name', 'lastname', 'middlename', 'email', 'phone']);
        }

        $this->merge($requestData);
    }

    /**
     * @param null $field
     * @return array|\array[][]|\ArrayAccess|mixed|\null[][]|\string[][]
     */
    public function getData($field = null)
    {
        $user = array_merge($this->recipient ?: [], $this->user ?: []);
        $recipient = array_merge($this->user ?: [], $this->recipient ?: []);

        $res = array_merge([
            'user_id' => $this->user()?->id,
            'user' => $user,
            'recipient' => $recipient,
            'shipping' => $this->input('shipping', []),
            'payment' => $this->input('payment', []),
            'client_comment' => $this->input('client_comment'),

            'server' => [
                'REMOTE_ADDR' => $this->server('REMOTE_ADDR'),
                'HTTP_USER_AGENT' => $this->server('HTTP_USER_AGENT'),
            ],
        ]);

        return $field ? Arr::get($res, $field) : $res;
    }
}
