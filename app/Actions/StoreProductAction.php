<?php

namespace App\Actions;

use App\Actions\CategoryVariationsCount;
//use App\Events\ProductSaved;
use App\Models\Product;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

final class StoreProductAction
{
    use AsAction;

    public function handle(array $data): Product
    {
        $data['type'] = Arr::get($data, 'type', Product::TYPE_PRODUCT);
        $data['brand_id'] = Arr::get($data, 'brand.id');
        $data['category_id'] = Arr::get($data, 'category.id');
        $data['productmodel_id'] = Arr::get($data, 'productmodel.id');
        $data['productparity_id'] = Arr::get($data, 'productparity.id');
        $data['created_at'] = Arr::get($data, 'created_at', now());
        $data['income_at'] = Arr::get($data, 'income_at', now());
        $data['status'] = Arr::get($data, 'status', Product::STATUS_PUBLISHED);

        if ($fields = Arr::get($data, 'fields')) {
            $data['fields'] = Product::prepareFieldsForSave($fields, Arr::get($data, 'notarrays'));
        }

        /** @var Product $product */
        $product = Product::create(Arr::only($data, [
            'type', 'name', 'status',
            'body', 'slug', 'fields', 'added', 'income_at', 'created_at',
            'category_id', 'brand_id', 'productmodel_id', 'productparity_id', 'variant_uuid', 'domain_id', 'feed_id'
        ]));

        // Терми: Категорії, Теги,...
        $product->syncTerms(Arr::get($data, 'terms', []), [$product->category_id]);
//        $product->markers()->sync(Arr::get($data, 'markers', []));

        if (isset($data['properties'])) {
            $product->properties()->sync(array_filter(\Arr::flatten(Arr::get($data, 'properties', []))));
        }

        // TODO: Товари коллекції
        if (isset($data['products'])) {
            $products = Arr::get($data, 'products', []);
            $ids = is_array(Arr::first($products))
                ? Arr::pluck($data['products'], 'id')
                : $products;

            $product->products()->sync($ids);
        }

        //$product->mediaManageRefresh($data, Auth::user());
        //ProductSaved::dispatch($product);

        if (!is_null($product->category)) {
            CategoryVariationsCount::dispatch($product->category);
        }

        return $product;
    }
}
