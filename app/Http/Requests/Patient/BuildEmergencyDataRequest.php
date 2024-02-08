<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;

class BuildEmergencyDataRequest extends FormRequest
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
            'systolic' => 'numeric',
            'diastolic' => 'numeric',
            'blood_sugar' => 'numeric',
            'weight' => 'numeric',
            'height' => 'numeric',
            'blood_type' => 'string',
            //'chronic_diseases_bad_habits' => 'array',
            'chronic_diseases_bad_habits.*' => 'string',
            'bloodPressure_change_date' => 'string',
            'bloodPressure_change_time' => 'string',
            'bloodSugar_change_date' => 'string',
            'bloodSugar_change_time' => 'string',
            'weightHeight_change_date' => 'string',
            'weightHeight_change_time' => 'string',
        ];
    }


    public function messages()
{
    return [
        'systolic.numeric' => 'Systolic must be a numeric value.',
        'diastolic.numeric' => 'Diastolic must be a numeric value.',
        'blood_sugar.numeric' => 'Blood sugar must be a numeric value.',
        'weight.numeric' => 'Weight must be a numeric value.',
        'height.numeric' => 'Height must be a numeric value.',
        'blood_type.string' => 'Blood type must be a string.',
        
        //'chronic_diseases_bad_habits.required' => 'Chronic diseases/bad habits are required.',
        //'chronic_diseases_bad_habits.array' => 'Chronic diseases/bad habits must be an array.',
        'chronic_diseases_bad_habits.*.string' => 'Each chronic disease/bad habit must be a string.',
        
        //'bloodPressure_change_date.required' => 'Blood pressure change date is required.',
        //'bloodPressure_change_date.date_format' => 'Blood pressure change date must be in the format YYYY/MM/DD.',
        
        //'bloodPressure_change_time.required' => 'Blood pressure change time is required.',
        //'bloodPressure_change_time.date_format' => 'Blood pressure change time must be in the format HH:MM.',
    
        //'bloodSugar_change_date.required' => 'Blood sugar change date is required.',
        //'bloodSugar_change_date.date_format' => 'Blood sugar change date must be in the format YYYY/MM/DD.',
        
        //'bloodSugar_change_time.required' => 'Blood sugar change time is required.',
        //'bloodSugar_change_time.date_format' => 'Blood sugar change time must be in the format HH:MM.',
        
        //'weightHeight_change_date.required' => 'Weight/height change date is required.',
        //'weightHeight_change_date.date_format' => 'Weight/height change date must be in the format YYYY/MM/DD.',
        
        //'weightHeight_change_time.required' => 'Weight/height change time is required.',
        //'weightHeight_change_time.date_format' => 'Weight/height change time must be in the format HH:MM.',
    ];
}

}
