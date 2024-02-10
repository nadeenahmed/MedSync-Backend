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
            'speciality_id' => 'required|exists:specialities,id',
            'years_of_experience' => 'numeric',
            'medical_degree' => 'required|string',
            'university' => 'required|string',
            'medical_board_organization' => 'string',
            'licence_information' => 'required|string', // Adjust validation based on your file storage solution
            
        ];
    }
}
