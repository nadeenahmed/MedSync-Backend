<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\DoctorRequest;
use App\Models\Doctor;
use App\Models\Specialities;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class DoctorDataController extends Controller
{
    public function Create(DoctorRequest $request) {
        $user = $request->user();
        $doctorName = $user->name;
    
        $validatedData = $request->validated();
    
        // Retrieve medical speciality
        $specialityName = str_replace('"', '', $validatedData['medical_speciality']);
        $medicalSpeciality = Specialities::where('english_name', $specialityName)->first();
        if (!$medicalSpeciality) {
            return response()->json(['error' => 'Medical speciality not found'], 404);
        }
    
        // Handle file uploads
        $medicalDegreePath = $this->handleFileUpload($request, 'medical_degree', 'public/medical-degrees');
        $profileImagePath = $this->handleFileUpload($request, 'profile_image', 'public/profile-images');
        $medicalBoardOrgPath = $this->handleFileUpload($request, 'medical_board_organization', 
        'public/medical-board-organizations');
        $licenceInfoPath = $this->handleFileUpload($request, 'licence_information', 'public/licence-information');
    
        // Create doctor record
        $doctor = Doctor::create([
            'user_id' => $user->id,
            'gender' => $validatedData['gender'],
            'years_of_experience' => $validatedData['years_of_experience'],
            'medical_degree' => $medicalDegreePath,
            'university' => $validatedData['university'],
            'speciality_id' => $medicalSpeciality->id,
            'medical_board_organization' => $medicalBoardOrgPath,
            'licence_information' => $licenceInfoPath,
            'phone' => $validatedData['phone'],
            'profile_image' => $profileImagePath,
        ]);
    
        return response()->json([
            'message' => 'Doctor information saved successfully',
            'doctor name' => "Dr. " . $doctorName,
            'doctor' => $doctor,
        ], 201);
    }
    
    private function handleFileUpload($request, $fileKey, $uploadDirectory) {
        if ($request->hasFile($fileKey)) {
            $file = $request->file($fileKey);
            $uniqueFileName = Str::uuid() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs($uploadDirectory, $uniqueFileName);
            return 'storage/' . $uploadDirectory . '/' . $uniqueFileName;
        }
        return null;
    }
//     public function index(Request $request)
//     {
//         return $request->user();
//     }
//     public function Create(DoctorRequest $request) {
//         $user = $this->index($request);
//         $doctorName = $user->name;

//         $specialityName = str_replace('"', '', $request->input('medical_speciality'));

//         // patient could provide arabic or english name but not both
//         $medicalSpeciality = Specialities::where(function ($query) use ($specialityName) {
//             $query->where('english_name', $specialityName);
//         })->first();
//         if (!$medicalSpeciality) {
//             return response()->json(['error' => 'Medical speciality not found'], 404);
//         }
//         if ($request->hasFile('medical_degree')) {
//             $medicalDegree = $request->file('medical_degree');
//             $uniqueFileName = Str::uuid() . '_' . $medicalDegree->getClientOriginalName();
//             $uploadDirectory = 'public/medical-degrees';
//             $filePath = $medicalDegree->storeAs($uploadDirectory, $uniqueFileName);
//             $relativePath = 'storage/medical-degrees/';
//             $medicalDegreePath = $relativePath . $uniqueFileName;
//         } else {
//             $medicalDegreePath = null;
//         }
//         if ($request->hasFile('profile_image')) {
//             $profileImage = $request->file('profile_image');
//             $uniqueFileName = Str::uuid() . '_' . $profileImage->getClientOriginalName();
//             $uploadDirectory = 'public/profile-images';
//             $filePath = $profileImage->storeAs($uploadDirectory, $uniqueFileName);
//             $relativePath = 'storage/profile-images/';
//             $profileImagePath = $relativePath . $uniqueFileName;
//         } else {
//             $profileImagePath = null;
//         }
//         if ($request->hasFile('medical_board_organization')) {
//             $medicalBoardOrg = $request->file('medical_board_organization');
//             $uniqueFileName = Str::uuid() . '_' . $medicalBoardOrg->getClientOriginalName();
//             $uploadDirectory = 'public/medical-board-organizations'; // Define your upload directory
//             $filePath = $medicalBoardOrg->storeAs($uploadDirectory, $uniqueFileName);
//             $relativePath = 'storage/medical-board-organizations/';
//             $medicalBoardOrgPath = $relativePath . $uniqueFileName;
//         } else {
//             $medicalBoardOrgPath = null;
//         }
//         if ($request->hasFile('licence_information')) {
//             $licenceInfo = $request->file('licence_information');
//             $uniqueFileName = Str::uuid() . '_' . $licenceInfo->getClientOriginalName();
//             $uploadDirectory = 'public/licence-information'; // Define your upload directory
//             $filePath = $licenceInfo->storeAs($uploadDirectory, $uniqueFileName);
//             $relativePath = 'storage/licence-information/';
//             $licenceInfoPath = $relativePath . $uniqueFileName;
//         } else {
//             $licenceInfoPath = null;
//         }

//         $doctor = Doctor::create([
//            'user_id' => $user->id,
//             'gender' => $doctorData['gender'],
//             'years_of_experience' => $doctorData['years_of_experience'],
//             'medical_degree' => $medicalDegreePath,
//             'university' => $doctorData['university'],
//             'speciality_id' => $doctorData['speciality_id'],
//             'medical_board_organization' => $medicalBoardOrgPath,
//             'licence_information' => $licenceInfoPath,
//             'phone' => $doctorData['phone'],
//             'profile_image' => $profileImagePath,

//         ]);

//         return response()->json(['message' => 'Doctor information saved successfully',
//         'doctor name' => "Dr." .$doctorName,
//         'doctor' => $doctor,
//     ], 201);
//     }
}