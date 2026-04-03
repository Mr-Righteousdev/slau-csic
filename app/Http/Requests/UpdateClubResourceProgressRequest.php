<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClubResourceProgressRequest extends FormRequest
{
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
            'status' => ['required', 'in:not_started,in_progress,completed'],
            'progress_percentage' => ['required', 'integer', 'min:0', 'max:100'],
            'completed_units' => ['nullable', 'integer', 'min:0'],
            'score' => ['nullable', 'integer', 'min:0'],
            'ranking' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
