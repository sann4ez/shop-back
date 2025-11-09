<?php

namespace App\Http\Admin\Controllers;

use App\Actions\Products\Variations\ReindexVariationsAction;
use App\Actions\Products\Variations\StoreVariationAction;
use App\Actions\Products\Variations\UpdateVariationAction;
use App\Events\VariationSaved;
use App\Http\Admin\Controllers\Controller;
use App\Http\Admin\Requests\ProductVariationRequest;
use App\Http\Admin\WebDestinations;
use App\Models\Media;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

final class VariationController extends Controller
{
    use WebDestinations;

    public function create(Product $product)
    {
        return response()->json([
            'html' => view('admin.products.modals.variation_create', compact('product'))
                ->render()
        ]);
    }

    public function store(ProductVariationRequest $request, Product $product)
    {
        if ($request->prevalidate) {
            return 'ok';
        }

        /** @var ProductVariation $var */
        $variant = StoreVariationAction::run($product, $request->all());
        $variant->mediaManage($request);


        /** @var ProductVariation $cloningProductVariation */
        if ($request->_cloning_images && $request->cloningProductVariation && ($cloningProductVariation = ProductVariation::find($request->cloningProductVariation))) {
            /** @var Media $media */
            foreach ($cloningProductVariation->getMedia('images') as $media) {
                $variant->mediaSaveExpand(['path' => $media->getPath()], 'images');
            }
        }

        return redirect()->back()
            ->with('success', trans('alerts.shop.success'));
    }


    public function edit(ProductVariation $productVariation)
    {
        $product = $productVariation->product;

        return response()->json([
            'html' => view('admin.products.modals.variation_edit', compact('product', 'productVariation'))
                ->render()
        ]);
    }

    public function update(ProductVariationRequest $request, ProductVariation $productVariation)
    {
        if ($request->prevalidate) {
            return 'ok';
        }

        /** @var ProductVariation $productVariation */
        $productVariation = UpdateVariationAction::run($productVariation, $request->all());
        $productVariation->mediaManage($request);

        return redirect()->back()
            ->with('success', trans('alerts.update.success'));
    }

    public function destroy(ProductVariation $productVariation)
    {
        $productVariation->delete();

        return redirect()->back()
            ->with('success', trans('alerts.destroy.success'));
    }

    public function editable(Request $request, ProductVariation $productVariation)
    {
        $request->validate([
            'name' => 'string|required',
            'value' => 'string|required',
        ]);

        $productVariation->setAttribute($request->name, $request->value);
        $productVariation->save();

//        VariationSaved::dispatch($productVariation);

        return response()
            ->json(['message' => trans('alerts.update.success')]);
    }

    public function default(ProductVariation $productVariation)
    {
        /** @var Product $product */
        $product = $productVariation->product;

        $product->variations()->update(['is_default' => false]);
        $productVariation->update(['is_default' => true]);

        VariationSaved::dispatch($productVariation);

        return redirect()->back()
            ->with('success', trans('alerts.update.success'));
    }

    public function cloning(Request $request, ProductVariation $productVariation)
    {
        /** @var Product $product */
        $product = $productVariation->product;

        // TODO
        $code = ProductVariation::generateValue('barcode');
        $sku = ProductVariation::generateValue('sku');
        $productVariation->slug = null;
        $productVariation->sku = $sku; //$productVariation->sku . '-' . $product->variations->count()+1;
        $productVariation->barcode = $code;
        //$productVariation->media = null;
        $productVariation->stock_qty = 0;

        return response()->json([
            'html' => view('admin.products.modals.variation_clone', compact('product', 'productVariation'))
                ->render()
        ]);
    }

    /**
     * Генерувати нове значення (штирхкод, артикул,..)
     *
     * @param Request $request
     * @param string $field
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateValue(Request $request, string $field)
    {
        $max = ProductVariation::generateValue($field);

        return \response()->json([
            'value' => $max
        ]);
    }

    public function task(Request $request)
    {
        $request->validate(['task' => 'required|in:reindex']);

        switch ($request->task) {
            case 'reindex': ReindexVariationsAction::dispatch();
        }

        return redirect()->back()
            ->with('success', trans('alerts.operation.success-queue'));
    }

    public function seoEdit(Request $request, ProductVariation $productVariation)
    {
        return response()->json([
            'html' => view('admin.products.modals.variation_seo', \compact('productVariation'))->render()
        ]);
    }

    /**
     * @param SeoRequest $request
     * @param ProductVariation $post
     * @param SaveSeoAction $action
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function seoSave(SeoRequest $request, ProductVariation $productVariation)
    {
        $productVariation->saveSeo($request->seo ?: [], $request->all());

        if ($request->ajax()) {
            return response()
                ->json(['message' => trans('alerts.update.success')]);
        }

        return redirect()->back()
            ->with('success', trans('alerts.update.success'));
    }
}
