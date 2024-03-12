<?php

namespace App\Http\Requests\Doctor;

use Illuminate\Foundation\Http\FormRequest;

class DoctorRequest extends FormRequest
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
           // 'speciality_id' => 'required|exists:specialities,id',
            'years_of_experience' => 'nullable|numeric',
            'medical_degree' => 'required|string',
            'university' => 'required|string',
            'medical_board_organization' => 'nullable|string',
            'licence_information' => 'required',
            'gender' => 'nullable|string', 
            'phone' => 'nullable|string',
            'profile_image' => 'nullable|string',  
        ];
    }
}
