<?php

namespace App\Http\Controllers\Admin;
use App\Models\Patient;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterationRequest;
use App\Models\EmergencyData;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use App\Notifications\NewPatientNotification;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    public function index()
    {
        try{
            $patients = Patient::with('user', 'EmergencyData')->get();
            if($patients->isEmpty()){
                $response = [
                    'message' => "there is no patients"
                ];
                return response()->json($response,200);
            }else{
                return response()->json($patients);

            }
            
            
        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $response = [
                'message' => 'No Patients in DB',
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
    public function show($id)
    {
        try{
            $patient = Patient::findOrFail($id);
            $patientEmergencyData = EmergencyData::where('patient_id', $id)->first();
            $user = User::findOrFail($patient['user_id']);
            
            $response = [
                'user' =>  $user,
                'patient' => $patient,
                'patient-emergency-data' => $patientEmergencyData,
            ];
            return response()->json($response,200);
        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $response = [
                'message' => 'Patient not found',
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
    public function create(RegisterationRequest $request)
    {
        try{
            $user=$request->validated();
            $randomPassword = Str::random(8);
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($randomPassword),
                'role' => 'patient',
                'status' => 'active',
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
            $PatientEmergencyData = EmergencyData::create([
                'patient_id' => $patient->id,
                'systolic' => $request->input('systolic'),
                'diastolic' => $request->input('diastolic'),
                'blood_sugar' => $request->input('blood_sugar'),
                'weight' => $request->input('weight'),
                'height' => $request->input('height'),
                'blood_type' => $request->input('blood_type'),
                'chronic_diseases_bad_habits' => $request->input('chronic_diseases_bad_habits'),
            ]);
            $user->notify(new NewPatientNotification($randomPassword));
            $response = [
                'user' =>  $user,
                'patient' => $patient,
                'patient-emergency-data' => $PatientEmergencyData      
            ];
            return response()->json($response, 200);
        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $response = [
                'message' => 'Patient not found',
                'errors' => 'error : '.$e,
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
    public function update(Request $request, $id)
    {
        try{
            $patient = Patient::findOrFail($id);
            $patientEmergencyData = EmergencyData::where('patient_id', $id)->first();
            $user = User::findOrFail($patient['user_id']);
            $patient->update($request->all());
            $patientEmergencyData->update($request->all());
            $user->update($request->all());
            $response = [
                'user' =>  $user,
                'patient' => $patient,
                'patient-emergency-data' => $patientEmergencyData,
                
            ];
            return response()->json($response, 200);

        }catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $response = [
                'message' => 'Patient not found',
                'errors' => 'error : '.$e,
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
    public function destroy($id)
    {
        try {
            $patient = Patient::findOrFail($id);
            $patientEmergencyData = EmergencyData::where('patient_id', $id)->first();
            $user = User::findOrFail($patient->user_id);

            $patient->delete();
            $patientEmergencyData->delete();
            $user->delete();

            $response = [
                'message' => 'Patient Deleted Successfully',
            ];
            return response()->json($response, 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $response = [
                'message' => 'Patient not found',
                'errors' => 'error : '.$e,
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
