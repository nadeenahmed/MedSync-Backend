<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Workplace;
use Illuminate\Http\Request;

class WorkPlacesController extends Controller
{
    public function AddWorkPlace(Request $request)
    {
        $user = $request->user();
        $doctor = Doctor::where('user_id', $user->id)->first();
            if (!$doctor) {
                return response()->json(['error' => 'Doctor not found'], 404);
            }
        $validatedData = $request->validate([
            'street' => 'required|string',
            'region' => 'required|string',
            'country' => 'required|string',
            'description' => 'nullable|string',
            // 'work_hours' => 'required|array',
            // 'work_hours.*.day' => 'required|string',
            // 'work_hours.*.start' => 'required|date_format:H:i',
            // 'work_hours.*.end' => 'required|date_format:H:i|after:work_hours.*.start',
        ]);

        $workplace = Workplace::create([
            'doctor_id' => $doctor->id,
            'street' => $validatedData['street'],
            'region' => $validatedData['region'],
            'country' => $validatedData['country'],
            'description' => $validatedData['description'],
        ]);

        //$workplace->workHours()->createMany($validatedData['work_hours']);

        return response()->json([
            'message' => 'Workplace added successfully',
            'work place' => $workplace,
        ]);
    }
}
