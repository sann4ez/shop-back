<?php

namespace App\Http\Admin\Controllers;

use App\Http\Admin\WebDestinations;
use App\Models\Variable;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

final class SettingsController extends Controller
{
    use WebDestinations;

    /**
     * @param string $section
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(string $sectionKey)
    {
        $sections = Variable::sectionsList('*', 'key');
        $section = $sections[$sectionKey] ?? abort(404, "Конфігураційна форма [{$sectionKey}] не знайдена");

        if (Arr::get($section, 'perm') && !\Gate::any(Arr::get($section, 'perm'))) {
            abort(403);
        }

        return view('admin.settings.sections', compact('section', 'sections'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Request $request)
    {
        $request->validate([
            'vars' => 'array',
            'vars.*' => 'nullable|string',
            'vars_array' => 'array',
            'vars_file.*' => 'nullable|file',
            'vars_file_content.*' => 'nullable|array',
        ]);

        if ($request->get('group')) {
            \Variable::setGroup($request->get('group'));
        }

        foreach ($request->get('vars', []) as $key => $value) {
            \Variable::save($key, $value);
        }

        // ключі змінних, яких не треба мержити
        $withoutReplaceRecursiveKeys = $request->_without_replace_recursive_keys
            ? Arr::wrap($request->_without_replace_recursive_keys)
            : [];

        foreach ($request->get('vars_array', []) as $key => $value) {
            if (!in_array($key, $withoutReplaceRecursiveKeys)) {
                $value = \array_replace_recursive(\Variable::getArray($key, []), Arr::wrap($value));
            }

            \Variable::saveArray($key, $value);
        }

        foreach ($request->get('vars_file_deleted', []) as $key => $value) {
            if ($value) {
                if (\Variable::get($key) && (Storage::disk('public')->exists(\Variable::get($key)))) {
                    Storage::disk('public')->delete(\Variable::get($key));
                }
                \Variable::save($key, null);
            }
        }

        foreach ($request->file('vars_file', []) as $key => $value) {
            if (\Variable::get($key) && (Storage::disk('public')->exists(\Variable::get($key)))) {
                Storage::disk('public')->delete(\Variable::get($key));
            }
            if ($request->no_rename_all_files || in_array($key, $request->get('no_rename_files', []))) {
                $fileName = Storage::disk('public')->putFileAs('variables', $value, $value->getClientOriginalName());
            } else {
                $fileName = Storage::disk('public')->putFile('variables', $value);
            }
            \Variable::save($key, $fileName);
        }

        // очищаємо кеш по вказаним ключам кешу.
        if ($keys = $request->_cache_clear_keys) {
            foreach (Arr::wrap($keys) as $key) {
                if (is_string($key)) {
                    Cache::forget($key);
                }
            }
        }

        foreach ($request->get('vars_file_content', []) as $key => $value) {
            $path = $value['path'] ?? '';
            $content = $value['content'] ?? '';

            $directory = dirname($path);
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            File::put($path, $content);
        }

        return redirect()->back()
            ->with('success', trans('alerts.update.success'));
    }
}
