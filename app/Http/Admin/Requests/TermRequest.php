<?php

namespace App\Http\Admin\Requests;

use App\Models\Term;
use Illuminate\Validation\Rule;

final class TermRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = optional($this->route('term'))->id;

        return [
            'status' => ['nullable', Rule::in(Term::statusesList('key'))],
            'name' => 'required|string|max:255',
            'body' => 'nullable|string|max:50000',
            'vocabulary' => 'required',
            'added' => 'nullable|array',
            'parent_id' => 'nullable|exists:terms,id',
            'google_merchant_id' => 'nullable|string',
            'slug' => 'sometimes|string|unique:terms,id,' . $id,
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->isMethod('post') || $this->has('slug')) {
            if ($slug = $this->slug ?? $this->name) {
                $this->merge([
                    'slug' => Term::slugGenerate($slug, $this->route('term')),
                ]);
            }
        }

        $this->mergeIfMissing([
            'status' => Term::STATUS_PUBLISHED,
        ]);
    }

    public function getData()
    {
        return $this->only('name', 'status', 'slug', 'body', 'added', 'parent_id', 'vocabulary',);
    }
}
