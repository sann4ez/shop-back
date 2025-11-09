<?php

namespace App\Listeners;

use App\Actions\GroupedAttributeVariationAction;
//use App\Actions\ReindexAfterGroupedMainVariationAction;
use App\Actions\ReindexVariationAction;

class ProductVariationReindexListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $variation = $event->productVariation;

        // TODO змінив місцями виконання 32 і 33, перевірити роботу !?
        ReindexVariationAction::run($variation);
        GroupedAttributeVariationAction::run($variation);

//        ReindexAfterGroupedMainVariationAction::run($variation);
    }
}
