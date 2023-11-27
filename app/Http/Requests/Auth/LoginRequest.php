<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            
            'email' => 'email',
            'password' => 'min:8', 
            

        ];
    }

    public function messages()
    {
        return [
            
            'email.email' => 'Please enter a valid email address.',
            'password.min' => 'The password must be at least 8 characters.',
           ];
    }
}
