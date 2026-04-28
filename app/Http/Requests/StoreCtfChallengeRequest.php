<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCtfChallengeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization will be handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ctf_category_id' => 'required|exists:ctf_categories,id',
            'title' => 'required|string',
            'slug' => 'required|string|unique:ctf_challenges,slug',
            'description' => 'nullable|string',
            'flag' => 'required|string|min:5',
            'points' => 'required|integer|min:1|max:10000',
            'difficulty' => 'required|in:easy,medium,hard,insane',
            'is_active' => 'boolean',
            'hint' => 'nullable|string',
            'hint_cost' => 'nullable|integer|min:0',
            'max_attempts' => 'nullable|integer|min:0',
            'tags' => 'nullable|array',
            'sort_order' => 'nullable|integer',
        ];
    }
}
