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
                $formattedPatients = [];

            foreach ($patients as $patient) {
                $user = $patient->user;
                $emergencyData = $patient->emergencyData;

                $formattedPatients[] = [
                    'id' => $patient->id,
                    'created_at' => $patient->created_at,
                    'updated_at' => $patient->updated_at,
                    'user_id' => $patient->user_id,
                    'gender' => $patient->gender,
                    'age' => $patient->age,
                    'address' => $patient->address,
                    'phone' => $patient->phone,
                    'marital_status' => $patient->marital_status,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'email_verified_at' => $user->email_verified_at,
                        'role' => $user->role,
                        'google_id' => $user->google_id,
                        'status' => $user->status,
                        'created_at' => $user->created_at,
                        'updated_at' => $user->updated_at,
                    ],
                    'emergency_data' => [
                        'id' => $emergencyData->id,
                        'created_at' => $emergencyData->created_at,
                        'updated_at' => $emergencyData->updated_at,
                        'patient_id' => $emergencyData->patient_id,
                        'systolic' => $emergencyData->systolic,
                        'diastolic' => $emergencyData->diastolic,
                        'blood_sugar' => $emergencyData->blood_sugar,
                        'weight' => $emergencyData->weight,
                        'height' => $emergencyData->height,
                        'blood_type' => $emergencyData->blood_type,
                        'chronic_diseases_bad_habits' => json_decode($emergencyData->chronic_diseases_bad_habits),
                    ],
                ];
            }

            return response()->json($formattedPatients,200);

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
                'id' => $patient->id,
                'created_at' => $patient->created_at,
                'updated_at' => $patient->updated_at,
                'user_id' => $patient->user_id,
                'gender' => $patient->gender,
                'age' => $patient->age,
                'address' => $patient->address,
                'phone' => $patient->phone,
                'marital_status' => $patient->marital_status,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'role' => $user->role,
                    'google_id' => $user->google_id,
                    'status' => $user->status,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ],
                'emergency_data' => [
                    'id' => $patientEmergencyData->id,
                    'created_at' => $patientEmergencyData->created_at,
                    'updated_at' => $patientEmergencyData->updated_at,
                    'patient_id' => $patientEmergencyData->patient_id,
                    'systolic' => $patientEmergencyData->systolic,
                    'diastolic' => $patientEmergencyData->diastolic,
                    'blood_sugar' => $patientEmergencyData->blood_sugar,
                    'weight' => $patientEmergencyData->weight,
                    'height' => $patientEmergencyData->height,
                    'blood_type' => $patientEmergencyData->blood_type,
                    'chronic_diseases_bad_habits' => json_decode($patientEmergencyData->chronic_diseases_bad_habits),
                ],
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
                'id' => $patient->id,
                'created_at' => $patient->created_at,
                'updated_at' => $patient->updated_at,
                'user_id' => $patient->user_id,
                'gender' => $patient->gender,
                'age' => $patient->age,
                'address' => $patient->address,
                'phone' => $patient->phone,
                'marital_status' => $patient->marital_status,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'role' => $user->role,
                    'google_id' => $user->google_id,
                    'status' => $user->status,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ],
                'emergency_data' => [
                    'id' => $PatientEmergencyData->id,
                    'created_at' => $PatientEmergencyData->created_at,
                    'updated_at' => $PatientEmergencyData->updated_at,
                    'patient_id' => $PatientEmergencyData->patient_id,
                    'systolic' => $PatientEmergencyData->systolic,
                    'diastolic' => $PatientEmergencyData->diastolic,
                    'blood_sugar' => $PatientEmergencyData->blood_sugar,
                    'weight' => $PatientEmergencyData->weight,
                    'height' => $PatientEmergencyData->height,
                    'blood_type' => $PatientEmergencyData->blood_type,
                    'chronic_diseases_bad_habits' => json_decode($PatientEmergencyData->chronic_diseases_bad_habits),
                ],
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
                'id' => $patient->id,
                'created_at' => $patient->created_at,
                'updated_at' => $patient->updated_at,
                'user_id' => $patient->user_id,
                'gender' => $patient->gender,
                'age' => $patient->age,
                'address' => $patient->address,
                'phone' => $patient->phone,
                'marital_status' => $patient->marital_status,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'email_verified_at' => $user->email_verified_at,
                    'role' => $user->role,
                    'google_id' => $user->google_id,
                    'status' => $user->status,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ],
                'emergency_data' => [
                    'id' => $patientEmergencyData->id,
                    'created_at' => $patientEmergencyData->created_at,
                    'updated_at' => $patientEmergencyData->updated_at,
                    'patient_id' => $patientEmergencyData->patient_id,
                    'systolic' => $patientEmergencyData->systolic,
                    'diastolic' => $patientEmergencyData->diastolic,
                    'blood_sugar' => $patientEmergencyData->blood_sugar,
                    'weight' => $patientEmergencyData->weight,
                    'height' => $patientEmergencyData->height,
                    'blood_type' => $patientEmergencyData->blood_type,
                    'chronic_diseases_bad_habits' => json_decode($patientEmergencyData->chronic_diseases_bad_habits),
                ],
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
