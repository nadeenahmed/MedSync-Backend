<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
class DoctorController extends Controller
{
    public function index()
    {
        $doctor = Doctor::with('user')->get();
        return response()->json($doctor);
    }
    public function show($id)
    {
        $doctor = Doctor::with('user')->find($id);
        
        $doctorArray = $doctor->toArray();
        $userArray = $doctor->user->toArray();
        $mergedData = array_merge($doctorArray, $userArray);
        unset($mergedData['user']);

        return response()->json($mergedData);
    }
    public function create(Request $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role' => 'doctor',
        ]);
        $DoctorPersonalData = [
            'gender' => $request->input('gender'),
            'years_of_exp' => $request->input('years_of_exp'),
            'clinic_address' => $request->input('clinic_address'),
            'clinic_phone' => $request->input('clinic_phone'),
            'medical_speciality' => $request->input('medical_speciality'),
            'user_id' => $user->id,
        ];
        $doctor = Doctor::create($DoctorPersonalData);
        
        $response = [
            'doctor' => $doctor,
            'user' =>  $user
        ];
        return response()->json($response, 200);
    }
    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);
        $user = User::findOrFail($doctor['user_id']);
        $doctor->update($request->all());
        $user->update($request->all());
        $response = [
            'doctor' => $doctor,
            'user' =>  $user
        ];
        return response()->json($response, 200);
    }
    public function destroy($id)
    {
        try {
            $doctor = Doctor::findOrFail($id);
            $user = User::findOrFail($doctor->user_id);

            $doctor->delete();
            $user->delete();

            $response = [
                'message' => 'Doctor Deleted Successfully',
            ];
            return response()->json($response, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $response = [
                'message' => 'Record not found',
                'errors' => $e,
            ];
            return response()->json($response, 404);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Internal Server Error',
                'errors' => 'error : '.$e,
            ];
            return response()->json($response, 500);
        }
    }
}
