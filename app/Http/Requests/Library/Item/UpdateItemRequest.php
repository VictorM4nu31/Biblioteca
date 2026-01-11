<?php

namespace App\Http\Requests\Library\Item;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateItemRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('edit items');
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:255',
            'publisher' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1000|max:' . (date('Y') + 1),
            'isbn' => [
                'nullable',
                'string',
                Rule::unique('items', 'isbn')->ignore($this->item),
            ],
            'description' => 'nullable|string',
            'type' => 'required|in:book,comic,magazine',
            'language' => 'required|string|max:10',
            'pages' => 'nullable|integer|min:1',
            'cover_image' => 'nullable|image|max:2048',
            'file' => 'nullable|file|mimes:pdf,epub,mobi|max:51200',
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ];
    }
}
