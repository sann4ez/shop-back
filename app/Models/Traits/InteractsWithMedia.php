<?php

namespace App\Models\Traits;

use Illuminate\Support\Arr;
use Spatie\Image\Enums\Fit;

trait InteractsWithMedia
{
    use \Fomvasss\MediaLibraryExtension\HasMedia\InteractsWithMedia;

    public function customMediaConversions(\Spatie\MediaLibrary\MediaCollections\Models\Media $media = null): void
    {
        // TODO Domain // $media->model

        $options = [];

        // TODO: Optimize N+1!!!
        if ($media->model?->domain_id && $media->model->domain) {
            \Domain::setSelected($media->model->domain);
        }

        switch ($media->model?->getMorphClass()) {
            case 'product':
            case 'variation':
            case 'product_variation':
                $options = [];
                $this->addOgMediaConversion('images');
                $this->addGoogleMerchantMediaConversion('images');
            break;
            case 'term':
                $options = [];
                $this->addOgMediaConversion('image');
            break;
        }

        // https://spatie.be/docs/image/v1/image-manipulations/overview
        // https://spatie.be/docs/image/v1/image-manipulations/resizing-images#crop
        foreach ($options as $option) {
            $conversion = $this->addMediaConversion($option['name']);
            if ($val = Arr::get($option, 'fit')) {
                list($method, $width, $height) = $val;
                $conversion->fit($method, $width, $height);
            }

            if ($val = Arr::get($option, 'format')) {
                $conversion ->format($val);
            }

            if ($val = Arr::get($option, 'quality')) {
                $conversion ->quality($val);
            }

            if ($val = Arr::get($option, 'performOn')) {
                $conversion->performOnCollections(...Arr::wrap($val));
            }
        }

//        $this->addMediaConversion('preview')
//            ->fit(Manipulations::FIT_FILL_MAX, 306, 325)
//            ->crop()
//            ->quality(95)
//            ->format(Manipulations::FORMAT_WEBP)
//            ->performOnCollections(...['images']);
    }

    protected function addOgMediaConversion($collectionName)
    {
        $this->addMediaConversion('og_image')
            ->fit(Fit::FillMax, 1200, 630)
            ->format('png')->background('#FFFFFF')
            ->performOnCollections($collectionName);
    }

    protected function addGoogleMerchantMediaConversion($collectionName)
    {
        $this->addMediaConversion('google_merchant')
            ->fit(Fit::FillMax, 450, 450)
            ->format('webp')
            ->performOnCollections($collectionName);
    }
}
