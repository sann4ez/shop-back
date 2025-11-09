<?php

namespace App\Http\Admin\Requests;

use App\Models\Page;
use Illuminate\Validation\Rule;

final class PageRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'body' => 'nullable|string',
            'template' => ['nullable', Rule::in(Page::templatesList('key'))],
            'status' => ['required', Rule::in(Page::statusesList('key'))],
            'slug' => [
                'nullable',
                'string',
                'alpha_dash',
                //Rule::unique('pages')->ignore(optional($this->page)->id),
            ],
            'added' => 'nullable|array',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->isMethod('post') || $this->has('slug')) {
            if ($slug = $this->slug ?? $this->name) {
                $this->merge([
                    'slug' => Page::slugGenerate($slug, $this->route('page')),
                ]);
            }
        }

//        $this->merge([
//            'added' => json_decode($this->input('added'), true),
//        ]);
    }
}
