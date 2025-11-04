<?php


namespace App\Http\Admin\Requests;


use Illuminate\Support\Carbon;

class FormRequest extends \Illuminate\Foundation\Http\FormRequest
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

    protected function prepareForValidationPhoneValues(array $fields = [], bool $withPlus = false)
    {
        $merges = [];
        foreach ($fields as $field) {
            if ($value = $this->get($field)) {
                $merges[$field] = $this->clearPhoneValue($value, $withPlus);
            }
        }

        $this->merge($merges);
    }

    protected function clearPhoneValue($value, bool $withPlus = false): string
    {
        if ($val = preg_replace('/[^0-9]/si', '', $value)) {
            return $withPlus ? ('+' . $val) : $val;
        }

        return $value;
    }

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

    protected function prepareForValidationDateValue(string $field, string $timeOfDay = null)
    {
        $appTZ = config('app.timezone');
        $clientTZ = config('app.timezone_client') ?: $appTZ;

        $merges = [];
        if ($value = $this->get($field)) {
            $datetime = Carbon::parse($value, $clientTZ);

            $datetime = match ($timeOfDay) {
                'start' => $datetime->startOfDay(),
                'end' => $datetime->endOfDay(),
                default => $datetime
            };

            $merges[$field] = $datetime->setTimezone($appTZ);
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
                $merges[$field] = false;
                if ($value === 'true' || $value === '1' || $value === true || $value === 1 || $value === 'TRUE') {
                    $merges[$field] = true;
                }
            }
        }

        $this->merge($merges);
    }
}
