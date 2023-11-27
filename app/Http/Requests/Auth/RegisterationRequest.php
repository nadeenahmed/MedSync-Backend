<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterationRequest extends FormRequest
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
            'name' => 'string',
            'email' => 'unique:users,email',
            'password' => 'min:8|confirmed', // 'confirmed' checks if password and password_confirmation match
            'phone' => [
                'regex:/^(011|012|010|015)[0-9]{8}$/',
            ],

        ];
    }

    public function messages()
    {
        return [
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already in use.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'phone.regex' => 'Please enter a valid Egyptian phone number.',
           ];
    }
}
