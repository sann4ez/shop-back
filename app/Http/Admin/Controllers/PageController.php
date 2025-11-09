<?php

namespace App\Http\Admin\Controllers;

use App\Models\Block;
use App\Models\Page;
use App\Http\Admin\Requests\PageRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

final class PageController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::latest();

        return view('admin.pages.index', ['pages' => $pages->paginate()]);
    }

    public function create()
    {
        return view('admin.pages.create');
    }

    public function store(PageRequest $request)
    {
        /** @var Page $page */
        $page = Page::create($request->only('name', 'body', 'template', 'status', 'slug', 'added'));

        $this->createBlocks($page);

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', trans('alerts.store.success'));
    }

    public function edit(Page $page)
    {
        return view('admin.pages.edit', compact('page'));
    }

    public function update(PageRequest $request, Page $page)
    {
        $page->update($request->only('name', 'body', 'template', 'status', 'slug', 'added'));

        return redirect()->route('admin.pages.edit', $page)
            ->with('success', trans('alerts.update.success'));
    }

    public function destroy(Request $request, Page $page)
    {
        $page->delete();

        return redirect()->route('admin.pages.index')
            ->with('success', trans('alerts.destroy.success'));
    }

    /**
     * Приєднати блоки.
     *
     * @param Request $request
     * @param Page $page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function blocksAttach(Request $request, Page $page)
    {
        $ids = Arr::wrap($request->ids);

        if ($request->cloning && $request->ids) {
            $ids = [];
            $blocks = Block::whereIn('id', $request->ids)->get();
            $mayUniqueSlug = boolval($request->may_unique_slug);

            foreach ($blocks as $block) {
                // Генеруємо новий slug
                $slug = Block::slugGenerate($block->slug, null, $mayUniqueSlug);

                // Створюємо новий блок без перекладів
                $newBlock = Block::create(array_merge(
                    ['slug' => $slug],
                    $block->only('type', 'options', 'ids', 'name', 'desc', 'content')
                ));

                $ids[] = $newBlock->id;
            }
        }

        $page->blocks()->attach($ids);

        return redirect()->back()
            ->with('success', 'Блоки успішно додані.');
    }

    /**
     * @param Page $page
     * @return array
     */
    protected function createBlocks(Page $page)
    {
        $mayUniqueSlug = boolval(request()->may_unique_slug ?? false);
        $ids = [];

        $oldestPageTemplate = Page::with('blocks')
            ->where('template', $page->template)
            ->where('id', '<>', $page->id)
            ->oldest()
            ->first();

        if ($oldestPageTemplate && $oldestPageTemplate->blocks->count()) {
            $blocks = $oldestPageTemplate->blocks;

            foreach ($blocks as $block) {
                // Генеруємо новий slug
                $slug = Block::slugGenerate($block->slug, null, $mayUniqueSlug);

                // Створюємо новий блок без перекладів
                $newBlock = Block::create(array_merge(
                    ['slug' => $slug],
                    $block->only('name', 'desc', 'content', 'type', 'options', 'ids')
                ));

                $ids[] = $newBlock->id;
            }
        } else {
            $templates = Page::templatesList('*', 'key');
            $pageTemplate = Arr::get($templates, $page->template);

            if ($pageTemplate && Arr::get($pageTemplate, 'blocks')) {
                $blockTypes = Block::typesList('*', 'key');

                foreach (Arr::get($pageTemplate, 'blocks') as $blockTypeKey) {
                    $slug = Block::slugGenerate(Str::slug($blockTypeKey), null, $mayUniqueSlug);
                    $blockName = ($blockTypes[$blockTypeKey]['name'] ?? 'Блок ') . " ({$page->name}) - НАПОВНІТЬ БЛОК!";

                    $newBlock = Block::create([
                        'name' => $blockName,
                        'slug' => $slug,
                        'type' => $blockTypeKey,
                    ]);

                    $ids[] = $newBlock->id;
                }
            }
        }

        $page->blocks()->attach($ids);

        return $ids;
    }

    /**
     * Відєднати блоки.
     *
     * @param Request $request
     * @param Page $page
     * @return \Illuminate\Http\RedirectResponse
     */
    public function blocksDetach(Request $request, Page $page)
    {
        $page->blocks()->wherePivotIn('id', Arr::wrap($request->ids))->detach();

        return redirect()->back()
            ->with('success', trans('alerts.destroy.success'));
    }

    /**
     * Впорядкувати блоки.
     *
     * @param Request $request
     * @param Page $page
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function blocksOrder(Request $request, Page $page)
    {
        $this->validate($request, [
            'data' => 'required|array'
        ]);

        foreach ($request->data ?? [] as $key => $id) {
            $bl = $page->blocks()->wherePivot('id', $id)->first();
            $bl->pivot->weight = $key;
            $bl->pivot->save();
        }

        return response()
            ->json(['message' => trans('alerts.update.success')]);
    }
}
