<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\DoctorRequest;
use App\Models\Doctor;
use App\Models\Specialities;
use Illuminate\Http\Request;

class DoctorDataController extends Controller
{
    public function index(Request $request)
    {
        return $request->user();
    }
    public function Create(DoctorRequest $request) {
        $user = $this->index($request);
        $DoctorName = $user->name;

        $specialityName = str_replace('"', '', $request->input('medical_speciality'));

        // patient could provide arabic or english name but not both
        $medicalSpeciality = Specialities::where(function ($query) use ($specialityName) {
            $query->where('english_name', $specialityName);
        })->first();
        if (!$medicalSpeciality) {
            return response()->json(['error' => 'Medical speciality not found'], 404);
        }


        $doctor = Doctor::create([ 
            'user_id' => $user->id,
            'speciality_id' => $medicalSpeciality->id,
            'years_of_experience' =>$request->input('years_of_experience'),
            'medical_degree'=>$request->input('medical_degree'),
            'university'=>$request->input('university'),
            'medical_board_organization	'=>$request->input('medical_board_organization'),
            'licence_information'=>$request->input('licence_information'),
            'gender'=>$request->input('gender'),
            'phone'=>$request->input('phone'),
            'profile_image'=>$request->input('profile_image'),
        ]);

        return response()->json(['message' => 'Doctor information saved successfully', 
        'doctor name' => "Dr." .$DoctorName,
        'doctor' => $doctor,
    ], 201);
    }
}
