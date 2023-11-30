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
            
            'email' => 'unique:users,email',
            'password' => 'confirmed', // 'confirmed' checks if password and password_confirmation match

        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'This email is already in use.',
            'password.confirmed' => 'The password confirmation does not match.',
           ];
    }
}
