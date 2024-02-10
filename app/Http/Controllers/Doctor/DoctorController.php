<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\DoctorRequest;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        return $request->user();
    }
    public function store(DoctorRequest $request) {
        $user = $this->index($request);
        $DoctorName = $user->name;
        $doctor = Doctor::where('user_id', $user->id)->first();
            if (!$doctor) {
                return response()->json(['errors' => 'Patient not found'], 404);
            }
        


        $doctor = Doctor::create($request->all());

        return response()->json(['message' => 'Doctor information saved successfully', 'doctor' => $doctor], 201);
    }
}
