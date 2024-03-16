<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\DoctorRequest;
use App\Models\Doctor;
use App\Models\DoctorApprovalRequest;
use App\Models\MedicalCollege;
use App\Models\MedicalDegree;
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


        $university = str_replace('"', '', $request->input('university'));
        $medical_degree = str_replace('"', '', $request->input('medical_degree'));
        $specialityName = str_replace('"', '', $request->input('medical_speciality'));
        $doctorName = $user->name;

        $validator = Validator::make($request->all(), [
            'years_of_experience' => 'nullable|numeric|max:80|min:0',
            'medical_degree' => 'required|string',
            'university' => 'required|string',
            'medical_board_organization' => 'nullable|string',
            'licence_information' => 'required',
            'gender' => ['nullable', 'string', 'in:' . strtolower('male') . ',' . strtolower('female')],
            'phone' => [
                'nullable',
                'string',
                'regex:/^(010|011|012|015)[0-9]{8}$/',
            ],
<<<<<<< HEAD
            'profile_image' => 'nullable|string',
=======
>>>>>>> 3dbc77a0c8b6d301d0779d5e1c3b4c5d1e194f1d
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }


        $medicalSpeciality = Specialities::where(function ($query) use ($specialityName) {
            $query->where('english_name', $specialityName)
                ->orWhere('arabic_name', $specialityName);
        })->first();
        // if (!$medicalSpeciality) {
        //     return response()->json(['error' => 'Medical speciality not found'], 404);
        // }

        $university = MedicalCollege::where('english_name', $university)
            ->orWhere('arabic_name', $university)
            ->first();
        if (!$university) {
            return response()->json(['error' => 'university not found'], 404);
        }

        $medical_degree = MedicalDegree::where('english_name', $medical_degree)
            ->orWhere('arabic_name', $medical_degree)
            ->first();
        if (!$medical_degree) {
            return response()->json(['error' => 'Medical Degree not found'], 404);
        }
        // Handle file uploads
<<<<<<< HEAD
        $profileImagePath = $this->handleFileUpload($request, 'Profile_Picture',
         'public/profile-pictures', 'storage/profile-pictures/');
        $licenceInfoPath = $this->handleFileUpload($request, 'licence_information',
         'public/doctor-licence-info-files', 'storage/doctor-licence-info-files/');
    
=======
        $profileImagePath = $this->handleFileUpload($request, 'Profile_Picture', 'public/profile-pictures', 'storage/profile-pictures/');
        $licenceInfoPath = $this->handleFileUpload($request, 'licence_information', 'public/doctor-licence-info-files', 'storage/doctor-licence-info-files/');

>>>>>>> 3dbc77a0c8b6d301d0779d5e1c3b4c5d1e194f1d
        // Create doctor record
        $user->profile_photo_path = $profileImagePath;
        $user->update($request->all());
        $doctor->update([
            'user_id' => $user->id,
            'gender' => $request['gender'],
            'years_of_experience' => $request['years_of_experience'],
            'medical_degree_id' => $medical_degree->id,
            'university_id' => $university->id,
            'speciality_id' => $medicalSpeciality->id,
            'medical_board_organization' => $request['medical_board_organization'],
            'licence_information' => $licenceInfoPath,
            'phone' => $request['phone'],
        ]);

        $approvalRequest = DoctorApprovalRequest::where('doctor_id',$doctor->id)->first();
        if (!$approvalRequest) {
            $approvalRequest = DoctorApprovalRequest::create([
                'doctor_id' => $doctor->id,
                'request_status' => 'pending',
            ]);
        }

        $doctor["Medical Speciality"] =
            [
                "english name" => $medicalSpeciality->english_name,
                "arabic name" => $medicalSpeciality->arabic_name,
            ];
        $doctor["university"] =
            [
                "english name" => $university->english_name,
                "arabic name" => $university->arabic_name,
            ];
        $doctor["medical Degree"] =
            [
                "english name" => $medical_degree->english_name,
                "arabic name" => $medical_degree->arabic_name,
            ];

        return response()->json([
            'message' => 'Doctor information saved successfully. Approval requested.',
            'approval_request' => $approvalRequest,
            'doctor name' => "Dr." . $doctorName,
            'doctor' => $doctor,
            'user' => $user
        ], 200);
    }
}