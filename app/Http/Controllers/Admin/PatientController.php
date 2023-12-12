<?php

namespace App\Http\Controllers\Admin;
use App\Models\Patient;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
class PatientController extends Controller
{
    public function index()
    {
        $patients = Patient::with('user')->get();

        return response()->json($patients);
    }
    public function show($id)
    {
        $patient = Patient::with('user')->find($id);

        return response()->json($patient);
    }
    public function create(Request $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);
        // $validatedData = $request->validate([
        //     'user_id' => 'required|exists:users,id',
        // ]);
        $newdata = [
            'gender' => $request->input('gender'),
            'age' => $request->input('age'),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'marital_status' => $request->input('marital_status'),
            'user_id' => $user->id,
        ];
        $patientData = array_merge($newdata);
        $patient = Patient::create($patientData);
       // $user = $patient->user;
        return response()->json($patient, 201);
    }
    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $validatedData = $request->validate([
            'user_id' => 'exists:users,id',
        ]);
        $newdata = [
            'gender' => $request->input('gender'),
            'age' => $request->input('age'),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'marital_status' => $request->input('marital_status'),
        ];
        $updatedPatient = array_merge($validatedData, $newdata);
        $patient->update($updatedPatient);
        $user = $patient->user;
        $user->update([
            'name' => $request->input('name'),
        ]);

        return response()->json($patient, 200);
    }
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();
        $response = [
            'message' => 'Deleted Successfully',
        ];
        return response()->json($response, 200);
    }
}
