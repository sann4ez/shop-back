<?php

namespace App\Http\Admin\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Admin\Requests\BlockRequest;
use App\Models\Page;

final class BlockController extends Controller
{
    public function index(Request $request)
    {
        $blocks = Block::filterable();

        return view('admin.blocks.index', ['blocks' => $blocks->with('translations')->paginate(100), 'type' => $this->getType($request->type)]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'type' => ['sometimes', 'required', Rule::in(Block::typesList('key'))]
        ]);

        return view('admin.blocks.create', ['type' => $this->getType($request->type)]);
    }

    public function store(BlockRequest $request)
    {
        /** @var Block $block */
        $block = Block::create($request->getData());

        if ($request->model_type && $request->model_id) {
            /** @var Page $model */
            $model = match ($request->model_type) {
                'page' => Page::find($request->model_id),
                default => null,
            };

            $model->blocks()->attach($block->id);
        }

        return redirect()->route('admin.blocks.edit', $block)
            ->with('success', trans('alerts.store.success'));
    }

    public function show(Block $block)
    {
        return response()->json([
            'html' => view('admin.blocks.modals.show', \compact('block'))->render()
        ]);
    }

    public function edit(Block $block)
    {
        return view('admin.blocks.edit', ['block' => $block, 'type' => $this->getType($block->type)]);
    }

    public function update(BlockRequest $request, Block $block)
    {
        $block->update($request->getData());

        return redirect()->back()
            ->with('success', trans('alerts.update.success'));
    }

    public function destroy(Block $block)
    {
        $block->delete();

        return redirect()->back()
            ->with('success', trans('alerts.destroy.success'));
    }

    public function order(Request $request)
    {
        $this->validate($request, [
            'data' => 'required|array'
        ]);

        $entities = build_linear_array_sort($request->data);

        foreach ($entities as $item) {
            optional(Block::find($item['id']))->update($item);
        }

        return response()
            ->json(['message' => trans('alerts.update.success')]);
    }

    public function cloning(Request $request, Block $block)
    {
        $block->slug = Block::slugGenerate($block->slug ?? $block->name);

        return view('admin.blocks.clone', ['block' => $block, 'type' => $this->getType($block->type)]);
    }

    protected function getType($type = null)
    {
        if ($type) {
            return Block::typesList('*', 'key')[$type] ?? abort(403, "Тип блоку [{$type}] не знайдено!");
        }

        return $type;
    }
}
