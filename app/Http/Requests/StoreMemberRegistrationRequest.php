<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreMemberRegistrationRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'student_id' => ['required', 'string', 'max:100', 'unique:users,student_id'],
            'phone' => ['required', 'string', 'max:20'],
            'program' => ['required', 'string', 'max:100'],
            'faculty' => ['nullable', 'string', 'max:100'],
            'year_of_study' => ['required', 'integer', 'min:1', 'max:6'],
            'profile_photo' => ['required', 'image', 'max:5120'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'terms' => ['accepted'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'student_id.required' => 'A student identification number is required for club records.',
            'profile_photo.required' => 'Please upload a clear profile photo for your member account.',
            'profile_photo.image' => 'The uploaded file must be an image.',
            'profile_photo.max' => 'Your profile photo should be 5MB or smaller.',
            'terms.accepted' => 'You need to accept the club platform terms before continuing.',
        ];
    }
}
