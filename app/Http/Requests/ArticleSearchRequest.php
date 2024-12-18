<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           'keyword' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'url' => 'nullable|string',
            'source' => 'nullable|string|max:255',
            'limit' => 'nullable|integer|max:50',
        ];
    }
}
