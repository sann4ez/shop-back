<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Controllers\Controller;
use App\Http\Admin\WebDestinations;
use App\Models\Attribute;
use App\Models\Property;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    use WebDestinations;

    public function index(Request $request)
    {
        $attributes = Attribute::query()->withCount('properties')->orderBy('weight', 'asc')->latest();

        return view('admin.eav.attributes', [
            'attributes' => collect($attributes->get()),
        ]);
    }

    public function store(Request $request)
    {
        Attribute::create($request->only('name', 'prefix', 'suffix', 'help', 'slug', 'in_filter', 'in_variant', 'in_specification', 'has_image', 'domain_id'));

        return redirect()
            ->to($this->destinationUrl(route('admin.attributes.index')))
            ->with('success', trans('alerts.store.success'));
    }

    public function update(Request $request, Attribute $attribute)
    {
        $attribute->update($request->only('name', 'prefix', 'suffix', 'help', 'in_filter', 'in_variant', 'in_specification', 'has_image'));

        return redirect()
            ->to($this->destinationUrl(route('admin.attributes.index')))
            ->with('success', trans('alerts.store.success'));
    }

    public function editable(Request $request, Attribute $attribute)
    {
        $request->validate([
            'name' => 'string|required',
            'value' => 'string|required',
        ]);

        $attribute->setAttribute($request->name, $request->value);
        $attribute->save();

        return response()->json(['message' => trans('alerts.update.success')]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function order(Request $request)
    {
        $this->validate($request, [
            'data' => 'required|array'
        ]);

        foreach ($request->data as $weight => $id) {
            Attribute::find($id)?->update(['weight' => $weight]);
        }

        return response()
            ->json(['message' => trans('alerts.update.success')]);
    }

    public function destroy(Attribute $attribute)
    {
        if ($attribute->properties->count()) {
            return redirect()
                ->to($this->destinationUrl(route('admin.attributes.index')))
                ->with('warning', trans('alerts.destroy.error_children'));
        }

        $attribute->delete();

        return redirect()
            ->to($this->destinationUrl(route('admin.attributes.index')))
            ->with('success', trans('alerts.store.success'));
    }
}
