<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\DoctorRequest;
use App\Models\Doctor;
use App\Models\Specialities;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Traits\FileUploadTrait;


class BuildProfileController extends Controller
{
    use FileUploadTrait;
    public function Create(Request $request)
    {
        $user = $request->user();
        $doctor = Doctor::Where('user_id', $user->id)->first();
        if (!$doctor) {
            return response()->json(['errors' => 'Doctor not found'], 404);
        }
        $doctorName = $user->name;
        $validator = Validator::make($request->all(), [
            'years_of_experience' => 'nullable|numeric|max:80|min:0',
            'medical_degree' => 'required|string',
            'university' => 'required|string',
            'medical_board_organization' => 'nullable|string',
            'licence_information' => 'required',
            'gender' => ['nullable', 'string', 'in:'.strtolower('male').','.strtolower('female')],
            'phone' => [
                'nullable',
                'string',
                'regex:/^(010|011|012|015)[0-9]{8}$/',
            ],
            'profile_image' => 'nullable|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        //$validatedData = $request->validated();
    
        // Validate medical speciality
        $specialityName = str_replace('"', '', $request->input('medical_speciality'));
        $medicalSpeciality = Specialities::where('english_name', $specialityName)->first();
        if (!$medicalSpeciality) {
            return response()->json(['error' => 'Medical speciality not found'], 404);
        }
        // Handle file uploads
        $profileImagePath = $this->handleFileUpload($request, 'Profile_Picture',
         'public/profile-pictures', 'storage/profile-pictures/');
        $licenceInfoPath = $this->handleFileUpload($request, 'licence_information',
         'public/doctor-licence-info-files', 'storage/doctor-licence-info-files/');
    
        // Create doctor record
        $user->profile_photo_path = $profileImagePath;
        $user->update($request->all());
        $doctor->update([
            'user_id' => $user->id,
            'gender' => $request['gender'],
            'years_of_experience' => $request['years_of_experience'],
            'medical_degree' => $request['medical_degree'],
            'university' => $request['university'],
            'speciality_id' => $medicalSpeciality->id,
            'medical_board_organization' => $request['medical_board_organization'],
            'licence_information' => $licenceInfoPath,
            'phone' => $request['phone'],
        ]);

        $doctor["Medical Speciality"] =
        [
            "english name" => $medicalSpeciality->english_name,
            "arabic name" => $medicalSpeciality->arabic_name,
        ];
        return response()->json([
            'message' => 'Doctor information saved successfully',
            'doctor name' => "Dr." . $doctorName,
            'doctor' => $doctor,
            'user' => $user
        ], 200);
    }

}