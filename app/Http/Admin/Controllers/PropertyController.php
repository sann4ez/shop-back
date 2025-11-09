<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\Controllers\Controller;
use App\Http\Admin\WebDestinations;
use App\Models\Attribute;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    use WebDestinations;

    public function index(Request $request)
    {
        $attribute = Attribute::findOrFail($request->attribute_id);
        $properties = $attribute
            ->properties()
            ->orderBy('weight', 'asc')
            ->latest();

        return view('admin.eav.properties', [
            'attribute' => $attribute,
            'properties' => $properties->get(),
        ]);
    }

    public function store(Request $request)
    {
        /** @var Property $property */
        $property = Property::create($request->only('value', 'prefix', 'suffix', 'color', 'attribute_slug', 'attribute_id'));
        $property->mediaManage($request);

        return redirect()
            ->to($this->destinationUrl(route('admin.properties.index', ['attribute_id' => $property->attribute->id])))
            ->with('success', trans('alerts.store.success'));
    }

    public function editable(Request $request, Property $property)
    {
        $request->validate([
            'name' => 'string|required',
            'value' => 'string|required',
        ]);

        $property->setAttribute($request->name, $request->value);
        $property->save();

        return response()->json(['message' => trans('alerts.update.success')]);
    }

    public function image(Request $request, Property $property)
    {
        $property->mediaManage($request);

        return redirect()
            ->to($this->destinationUrl(route('admin.properties.index', ['attribute_id' => $property->attribute->id])))
            ->with('success', trans('alerts.store.success'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function order(Request $request)
    {
        $this->validate($request, [
            'data' => 'required|array'
        ]);

        foreach ($request->data as $weight => $id) {
            Property::find($id)?->update(['weight' => $weight]);
        }

        return response()
            ->json(['message' => trans('alerts.update.success')]);
    }

    public function destroy(Property $property)
    {
        $property->delete();

        return redirect()
            ->to($this->destinationUrl(route('admin.properties.index', ['attribute_id' => $property->attribute->id])))
            ->with('success', trans('alerts.store.success'));
    }
}
