<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\Hash;

trait UserTrait
{
    /**
     * @param $value
     */
    public function setPhoneAttribute($value)
    {
        if ($value) {
            $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
        } else {
            $this->attributes['phone'] = null;
        }
    }

    /**
     * @return string
     */
    public function getAvatar(): string
    {
        // TODO: or media or socialite
        return \Avatar::create($this->fullname ?: $this->email ?: $this->id)->setDimension(150)->setFontSize(56)->toBase64();
    }

    /**
     * TODO: Deprecated.
     *
     * @return mixed
     */
    public function getRole()
    {
        return $this->role;
    }

    public function markEmailAsUnverified()
    {
        return $this->forceFill([
            'email_verified_at' => null,
        ])->save();
    }

    public function setEmailVerified(bool $val = true)
    {
        if ($val === true && $this->hasVerifiedEmail() === false) {
            $this->markEmailAsVerified();
        } elseif ($val === false && $this->hasVerifiedEmail() === true) {
            $this->markEmailAsUnverified();
        }
    }

    /**
     * @return bool
     */
    public function isOnline(): bool
    {
        if ($this->activity_at) {
            return $this->activity_at->addMinute(3)->isFuture();
        }

        return false;
    }

    public function telegramStartUrl(): string
    {
        if ($un = config('services.telegram-bot-api.name')) {
            return "https://t.me/{$un}?start={$this->id}";
        }

        return '';
    }

        public function getTitleStr(): string
    {
        return implode(' | ', array_filter([
            $this->fullname,
            $this->email,
            $this->phone,
        ]));
    }
}
