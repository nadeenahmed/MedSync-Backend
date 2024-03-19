<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\DoctorRequest;
use App\Mail\DoctorApprovalMail;
use App\Models\Doctor;
use App\Models\DoctorApprovalRequest;
use App\Models\MedicalCollege;
use App\Models\MedicalDegree;
use App\Models\Specialities;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Mail;

class BuildProfileController extends Controller
{
    use FileUploadTrait;
    public function Create(Request $request)
    {
        $user = $request->user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        if (!$doctor) {
            return response()->json(['errors' => 'Doctor not found'], 404);
        }

        // $pendingRequest = DoctorApprovalRequest::where('doctor_id', $doctor->id)
        //                     ->where('request_status', 'pending')
        //                     ->first();

        // $rejectedRequest = DoctorApprovalRequest::where('doctor_id', $doctor->id)
        //                     ->where('request_status', 'rejected')
        //                     ->first();

        // $acceptedRequest = DoctorApprovalRequest::where('doctor_id', $doctor->id)
        //                     ->where('request_status', 'accepted')
        //                     ->first();

        // if ($pendingRequest) {
        //     return response()->json([
        //         'message' => 'Your approval request is already pending. Please wait for a response.',
        //         'approval_request' => $pendingRequest,
        //         'doctor_name' => "Dr. " . $user->name,
        //     ], 200);
        // }
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
        $profileImagePath = $this->handleFileUpload($request, 'Profile_Picture', 'public/profile-pictures', 'storage/profile-pictures/');
        $licenceInfoPath = $this->handleFileUpload($request, 'licence_information', 'public/doctor-licence-info-files', 'storage/doctor-licence-info-files/');

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
            //Mail::to($user->email)->send(new DoctorApprovalMail($doctorName));
        return response()->json([
            'message' => 'Doctor information saved successfully. Approval requested.',
            'approval_request' => $approvalRequest,
            'doctor name' => "Dr." . $doctorName,
            'doctor' => $doctor,
            'user' => $user
        ], 200);
    }
}
