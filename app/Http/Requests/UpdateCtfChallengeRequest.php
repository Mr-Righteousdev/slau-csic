<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCtfChallengeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ctf_category_id' => 'required|exists:ctf_categories,id',
            'title' => 'required|string',
            'slug' => 'nullable|string',
            'description' => 'nullable|string',
            'flag' => 'nullable|string|min:5',
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
