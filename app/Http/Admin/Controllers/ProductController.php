<?php

namespace App\Http\Admin\Controllers;

use App\Actions\ReindexProductAction;
use App\Actions\StoreProductAction;
use App\Actions\SyncAttrsPropertiesAction;
use App\Actions\UpdateProductAction;
use App\Actions\UpdateVariationAction;
use App\Http\Admin\Controllers\Controller;
use App\Http\Admin\Requests\ProductRequest;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use App\Models\Term;
use App\Actions\StoreVariationAction;

final class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->_view === 'variations') {
            return $this->indexVariations($request);
        }

//        if (\Domain::getOpt('products.has_variations') || $request->_view === 'groups') {
//            return $this->indexGroups($request);
//        }

        return $this->indexVariations($request);
    }

    protected function indexGroups(Request $request)
    {
        $products = Product::withTrans([
                'variation', 'variation',
                'variations', 'variations.media',
                'variations.product',  'variations.product.media',
                'variations.properties', 'variations.properties.attribute',
                'category', 'category',
            ])->where(fn ($p) => $p->whereHas('variations', fn($v) => $v->filterable())->orDoesntHave('variations'))
                ->filterable($request->f ?? [])
            //->withCount('variations')
            ->latest('income_at');

        $variationsCount = ProductVariation::count();

        return view('admin.products.groups', ['products' => $products->paginate(), 'variationsCount' => $variationsCount]);
    }

    protected function indexVariations(Request $request)
    {
        $variations = ProductVariation::with([
            'media', 'product.media',
            'properties', 'properties.attribute',
        ])->filterable()->whereHas('product');

        return view('admin.products.variations', ['variations' => $variations->paginate(100)]);
    }

    public function create(Request $request)
    {
        $category = null;
        if ($catId = $request->category_id) {
            $category = Term::find($catId);
        }

        return view('admin.products.create', \compact('category'));
    }

    public function store(ProductRequest $request)
    {
        $product = StoreProductAction::run($request->all());
        $product->mediaManage($request);

        if ($data = $request->variation) {
            $variant = StoreVariationAction::run($product, $data);
        }

        return redirect()
            ->route('admin.products.edit', [$product, 'action' => isset($variant) ? '' : 'variant'])
            ->with('success', trans('alerts.store.success'));
    }

    public function edit(Request $request, Product $product)
    {
        $product->load([
            //'variations.media',
            'variations',
            'variations.properties','variations.properties.attribute',
        ]);
        $res['product'] = $product;
        $res['productVariation'] = $product->variation;

        if ($request->_tab === 'attrs') {
            $res['attributes'] = $product->category->attrs()
                ->with(['properties', 'properties'])
                ->where('in_variant', false)
                ->get();
        }

        return view('admin.products.edit', $res);
    }

    public function update(ProductRequest $request, Product $product)
    {
        UpdateProductAction::run($product, $request->all());
        $product->mediaManage($request);

        if (($vId = $request->input('variation.id')) && ($data = $request->variation)) {
            UpdateVariationAction::run($product->variations->where('id', $vId)->first(), $data);
        } elseif ($data = $request->variation) {
            StoreVariationAction::run($product, $data);
        }

        return redirect()
            ->route('admin.products.edit', [$product])
            ->with('success', trans('alerts.update.success'));
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->back()
            ->with('success', trans('alerts.destroy.success'));
    }

    /**
     * TODO: Deprecated!
     *
     * @param Request $request
     * @param Product $product
     * @param SyncAttrsPropertiesAction $action
     * @return \Illuminate\Http\RedirectResponse
     */
    public function attrsSave(Request $request, Product $product, SyncAttrsPropertiesAction $action)
    {
        $action->handle($product, $request->get('properties', []));

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', trans('alerts.update.success'));
    }

    public function sync(Request $request)
    {
        switch ($request->action) {
            case 'changed':
                $data = \array_filter($request->only('status', 'brand_id', 'category_id'));
                if ($request->select === 'all') {
                    Product::query()->update($data);
                    Product::all()->each(fn($p) => ReindexProductAction::dispatch($p));
                } elseif ($request->ids) {
                    Product::whereIn('id', $request->ids)->update($data);
                    Product::whereIn('id', $request->ids)->each(fn($p) => ReindexProductAction::dispatch($p));
                }
                break;
            case 'removed':
                if ($request->select === 'all') {
                    //Product::query()->delete();
                } elseif ($request->ids) {
                    Product::whereIn('id', $request->ids)->delete();
                }
                break;
        }

        return response()
            ->json(['message' => trans('alerts.update.success')], \Illuminate\Http\Response::HTTP_OK);
    }
}
