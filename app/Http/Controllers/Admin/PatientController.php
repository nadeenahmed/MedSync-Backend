<?php

namespace App\Http\Controllers\Admin;
use App\Models\Patient;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $patient = Patient::create($validatedData);

        return response()->json($patient, 201);
    }
    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $patient->update($validatedData);

        return response()->json($patient, 200);
    }
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return response()->json(null, 204);
    }
}
