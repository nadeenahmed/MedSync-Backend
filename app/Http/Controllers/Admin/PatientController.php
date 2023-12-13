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
        
        $patientArray = $patient->toArray();
        $userArray = $patient->user->toArray();
        $mergedData = array_merge($patientArray, $userArray);
        unset($mergedData['user']);

        return response()->json($mergedData);
    }
    public function create(Request $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role' => 'patient',
        ]);
        $PatientPersonalData = [
            'gender' => $request->input('gender'),
            'age' => $request->input('age'),
            'address' => $request->input('address'),
            'phone' => $request->input('phone'),
            'marital_status' => $request->input('marital_status'),
            'user_id' => $user->id,
        ];
        $patient = Patient::create($PatientPersonalData);
        
        $response = [
            'patient' => $patient,
            'user' =>  $user
        ];
        return response()->json($response, 200);
    }
    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);
        $user = User::findOrFail($patient['user_id']);
        $patient->update([
            'gender' => $request->input('gender', $patient->gender),
            'age' => $request->input('age', $patient->age),
            'address' => $request->input('address', $patient->address),
            'phone' => $request->input('phone', $patient->phone),
            'marital_status' => $request->input('marital_status', $patient->marital_status),
        ]);
    
        $user->update([
            'name' => $request->input('name', $user->name),
            'email' => $request->input('email', $user->email),
        ]);
        $response = [
            'patient' => $patient,
            'user' =>  $user
        ];
        return response()->json($response, 200);
    }
    public function destroy($id)
    {
        try {
            $patient = Patient::findOrFail($id);
            $user = User::findOrFail($patient->user_id);

            $patient->delete();
            $user->delete();

            $response = [
                'message' => 'Patient Deleted Successfully',
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
