<?php

namespace App\Actions;

use App\Actions\CategoriesVariationsCount;
//use App\Events\ProductSaved;
use App\Models\Product;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;

final class UpdateProductAction
{
    use AsAction;

    public function handle(Product $product, array $data): Product
    {
        $prevTerms = Arr::get($product->variations()->first()?->index, 'categories', []);
        $newTerms = Arr::get($data, 'terms.product_categories', []) ?: Arr::get($data, 'category', []);
        $idsTerms = array_merge($prevTerms, $newTerms);

        $data['type'] = Arr::get($data, 'type', Product::TYPE_PRODUCT);
        if (isset($data['brand']['id'])) {
            $data['brand_id'] = Arr::get($data, 'brand.id');
        }
        if (isset($data['category']['id'])) {
            $data['category_id'] = Arr::get($data, 'category.id');
        }
        if (isset($data['productmodel']['id'])) {
            $data['productmodel_id'] = Arr::get($data, 'productmodel.id');
        }
        if (isset($data['productparity']['id'])) {
            $data['productparity_id'] = Arr::get($data, 'productparity.id');
        }
        $data['status'] = Arr::get($data, 'status', Product::STATUS_PUBLISHED);
        if ($fields = Arr::get($data, 'fields')) {
            $data['fields'] = Product::prepareFieldsForSave($fields, Arr::get($data, 'notarrays'));
        }
        // TODO: Товари з категоріями оновлюються нормально
        $product->update(Arr::only($data, [
            'type', 'name', 'status',
            'body', 'slug', 'fields', 'added', 'income_at', 'created_at',
            'category_id', 'brand_id', 'productmodel_id', 'productparity_id', 'variant_uuid', 'domain_id',
        ]));

        // Терми: Категорії, Теги,...
        $product->syncTerms(Arr::get($data, 'terms', []), [$product->category_id]);

        if (isset($data['properties'])) {
            $product->properties()->sync(array_filter(\Arr::flatten(Arr::get($data, 'properties', []))));
        }

        // Товари коллекції
        if (isset($data['products'])) {
            $products = Arr::get($data, 'products', []);
            $ids = is_array(Arr::first($products))
                ? Arr::pluck($data['products'], 'id')
                : $products;

            $product->products()->sync($ids);
        }

        $product->refresh();
        //$product->mediaManageRefresh($data, Auth::user());
//        ProductSaved::dispatch($product);

       CategoriesVariationsCount::dispatch($idsTerms);
//        CategoryVariationsCount::dispatch($product->category);
//        if ($prevCategory && $prevCategory->id !== $product->category->id) {
//            CategoryVariationsCount::dispatch($prevCategory);
//        }

        return $product;
    }
}
