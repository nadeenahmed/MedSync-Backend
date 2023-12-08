<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class EmailVerificationRequest extends FormRequest
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
            'email' => ['exists:users'],
            'otp' => ['max:4'],
        ];
    }

    public function messages()
    {
        return [
            'email.exists' => 'Email not found',
            'otp.max' => 'The code must be 4 numbers.',
           ];
    }
}
