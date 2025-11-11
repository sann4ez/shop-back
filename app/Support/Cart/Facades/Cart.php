<?php

namespace App\Support\Cart\Facades;

class Cart extends \Illuminate\Support\Facades\Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \App\Support\Cart\Cart::class;
    }
}