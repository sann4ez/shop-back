<?php

namespace App\Http\Admin\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Block;

class BlockRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'slug' => ['sometimes', 'string'],
            'status' => ['required', Rule::in(Block::statusesList('key'))],
//            'type' => ['required', Rule::in(Block::typesList('key'))],
            'name' => ['nullable', 'string', 'max:10000'],
            'desc' => ['nullable', 'string'],
            'content' => ['nullable', 'array'],
            'options' => ['nullable', 'array'],
            'ids' => ['nullable', 'array'],
            'comment' => ['nullable', 'string'],
            'locales' => 'nullable|array',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->isMethod('post') || $this->has('slug')) {
            if ($slug = $this->slug ?? $this->name) {
                $this->merge([
                    'slug' => Block::slugGenerate($slug, $this->route('block'), true),
                ]);
            }
        }
    }

    public function getData(): array
    {
        $content = [];
        $notarrays = is_array($this->get('notarrays', ''))
            ? $this->get('notarrays')
            : explode(',', $this->get('notarrays') ?: '');

        foreach ($this->get('content', []) as $key => $value) {
            $content[$key] = is_array($value) && !in_array($key, $notarrays) ? array_values($value) : $value;
        }

        return $this->only('status', 'type', 'slug', 'name', 'desc', 'options', 'ids', 'locales', 'comment') + ['content' => $content];
    }
}
