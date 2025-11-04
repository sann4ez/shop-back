<?php

namespace App\Http;

use Illuminate\Support\Carbon;

abstract class FormRequest extends \Illuminate\Foundation\Http\FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @param string ...$fields
     * @return void
     */
    protected function prepareForValidationPhoneValues(string ...$fields)
    {
        $merges = [];
        foreach ($fields as $field) {
            if ($value = $this->input($field)) {
                $merges[$field] = $this->getClearPhoneValue($value);
            }
        }

        $this->merge($merges);
    }

    protected function getClearPhoneValue($value, bool $withPlus = false): string
    {
        if ($val = preg_replace('/[^0-9]/si', '', $value)) {
            return $withPlus ? ('+' . $val) : $val;
        }

        return $value;
    }

    /**
     * TODO: Deprecated краще замінити цю на prepareForValidationDateValue
     *
     * @param string ...$fields
     * @return void
     */
    protected function prepareForValidationDateValues(string ...$fields)
    {
        $appTZ = config('app.timezone');
        $clientTZ = config('app.timezone_client') ?: $appTZ;

        $merges = [];
        foreach ($fields as $field) {
            if ($value = $this->get($field)) {
                $merges[$field] = Carbon::parse($value, $clientTZ)
                    ->setTimezone($appTZ);
            }
        }

        $this->merge($merges);
    }

    /**
     * @param string $field
     * @param string|null $timeOfDay
     * @param bool $useClientTimezone
     * @return void
     */
    protected function prepareForValidationDateValue(string $field, string $timeOfDay = null, bool $useClientTimezone = true)
    {
        $appTZ = config('app.timezone');
        $clientTZ = $useClientTimezone ? (config('app.timezone_client') ?: $appTZ) : $appTZ;

        $merges = [];
        if ($value = $this->get($field)) {
            $datetime = Carbon::parse($value, $clientTZ);

            $datetime = match ($timeOfDay) {
                'start' => $datetime->startOfDay(),
                'end' => $datetime->endOfDay(),
                default => $datetime
            };

            $merges[$field] = $datetime->setTimezone($appTZ);
        } elseif ($value === '0') {
            $merges[$field] = null;
        }

        $this->merge($merges);
    }

    protected function prepareForValidationDatetimeValues(string ...$fields)
    {
        $appTZ = config('app.timezone');
        $clientTZ = config('app.timezone_client') ?: $appTZ;

        $merges = [];
        foreach ($fields as $field) {
            if ($value = $this->get($field)) {
                $merges[$field] = Carbon::parse($value, $clientTZ)
                    ->setTimezone($appTZ)
                    ->toString();
            }
        }

        $this->merge($merges);
    }

    public function prepareForValidationBooleanValues(string ...$fields)
    {
        $merges = [];
        foreach ($fields as $field) {

            if ($value = $this->get($field)) {
                //$merges[$field] = false;
                if ($value === 'true' || $value === '1' || $value === true || $value === 1 || $value === 'TRUE') {
                    $merges[$field] = true;
                } elseif ($value === 'false' || $value === '0' || $value === false || $value === 0 || $value === 'FALSE') {
                    $merges[$field] = false;
                }
            }
        }

        $this->merge($merges);
    }

    /**
     *
     * Валідація способу доставки (для коризини, профіля юзера).
     *
     * @param $res
     * @return array
     */
    protected function rulesShipping($res): array
    {
        if ($this->input('shipping.method') === 'novaposhta') {
            $res = array_merge($res, [
                'shipping.novaposhta.city' => 'sometimes|string',                   // TODO: Deprecated
                'shipping.novaposhta.warehouse' => 'sometimes|string',              // TODO: Deprecated

                'shipping.novaposhta.CityRef' => 'sometimes|string',        // api.DeliveryCity
                'shipping.novaposhta.CityName' => 'required|string',        // api.Present
                'shipping.novaposhta.WarehouseRef' => 'sometimes|string',   // api.Ref
                'shipping.novaposhta.WarehouseName' => 'required|string',   // api.Description
            ]);
        } elseif ($this->input('shipping.method') === 'novaposhta_locker') {
            $res = array_merge($res, [
                'shipping.novaposhta_locker.CityRef' => 'sometimes|string',         // api.DeliveryCity
                'shipping.novaposhta_locker.CityName' => 'required|string',         // api.Present
                'shipping.novaposhta_locker.WarehouseRef' => 'sometimes|string',    // api.Ref
                'shipping.novaposhta_locker.WarehouseName' => 'required|string',    // api.Description
            ]);
        } elseif ($this->input('shipping.method') === 'novaposhta_courier') {
            $res = array_merge($res, [
                'shipping.novaposhta_courier.SettlementTypeCode' => 'sometimes|string',     // api.SettlementTypeCode - тип нас. пункут скорочено, м., с., смт.
                'shipping.novaposhta_courier.RecipientArea' => 'required|string',           // api.Area - область, Київська
                'shipping.novaposhta_courier.RecipientAreaRegions' => 'sometimes|nullable|string',    // api.Region - район, Броварський
                'shipping.novaposhta_courier.RecipientCityName' => 'required|string',       // api.MainDescription - місто
                'shipping.novaposhta_courier.RecipientAddressName' => 'required|string',    // вулиця
                'shipping.novaposhta_courier.RecipientHouse' => 'sometimes|string',         // будинок - !!! Поле не обов'язкове якщо без створення TTN, інакше поле обов'язкове !!!
                'shipping.novaposhta_courier.RecipientFlat' => 'nullable|numeric',           // квартира - НП валідує номер (цифру)
            ]);
        } elseif ($this->input('shipping.method') === 'ukrposhta') {
            $res = array_merge($res, [
                'shipping.ukrposhta.region' => 'required|string',
                'shipping.ukrposhta.region_id' => 'sometimes|string',
                'shipping.ukrposhta.city' => 'required|string',
                'shipping.ukrposhta.city_id' => 'sometimes|string',
                'shipping.ukrposhta.warehouse' => 'sometimes|string',
                'shipping.ukrposhta.postcode' => 'required|string',
            ]);
        } elseif ($this->input('shipping.method') === 'international') {
            $res = array_merge($res, [
                'shipping.international.country' => 'sometimes|string',
                'shipping.international.region' => 'required|string',
                'shipping.international.city' => 'required|string',
                'shipping.international.zipcode' => 'required|string',
                'shipping.international.street' => 'required|string',
                'shipping.international.house' => 'required|string',
                'shipping.international.apartment' => 'nullable|string',
            ]);
        } elseif ($this->input('shipping.method') === 'address') {
            $res = array_merge($res, [
                'shipping.address.region' => 'sometimes|string',
                'shipping.address.city' => 'required|string',
                'shipping.address.street' => 'required|string',
                'shipping.address.house' => 'sometimes|string',
                'shipping.address.apartment' => 'nullable|string',
            ]);
        }

        return $res;
    }
}
