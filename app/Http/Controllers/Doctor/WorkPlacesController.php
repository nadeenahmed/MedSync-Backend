<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Doctor;
use App\Models\DoctorApprovalRequest;
use App\Models\Region;
use App\Models\Workplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkPlacesController extends Controller
{
    public function GetDoctorWorkPlaces(Request $request)
    {
        $user = $request->user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }
        $workplaces = Workplace::where('doctor_id', $doctor->id)->get();
        if ($workplaces->isEmpty()) {
            return response()->json(['message' => 'No Clinics Available'], 200);
        }
        foreach ($workplaces as $workplace) {
            $workplace->TodayPatient=0;
            $workplace->PatientVisits=[];
            $workplace->work_days = json_decode($workplace->work_days);
            $region = Region::find($workplace->region_id);
            $country = Country::find($workplace->country_id);
            $workplace["Clinic_Name"] = $region->english_name;
            $workplace["region" ] = $region
                ? [
                    "english_name" => $region->english_name,
                    "arabic_name" => $region->arabic_name,
                ]
                : null;
                $workplace["country"] = $country
                ? [
                    "english_name" => $country->english_name,
                    "arabic_name" => $country->arabic_name,
                ]
                : null;    
        }
        return response()->json(['workplaces' => $workplaces], 200);
    }

    public function AddWorkPlace(Request $request)
    {
        $user = $request->user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }
        $doctorId = $doctor->id;
        // $DoctorRequestFound = DoctorApprovalRequest::whereExists(function ($query) use ($doctorId) {
        //     $query->select(DB::raw(1))
        //             ->from('doctor_approval_requests')
        //             ->where('doctor_id', $doctorId);
        // })->exists();

        //if (!$DoctorRequestFound) {

            $region = str_replace('"', '', $request->input('region'));
            $country = str_replace('"', '', $request->input('country'));

            $region = Region::where(function ($query) use ($region) {
                $query->where('english_name', $region)
                    ->orWhere('arabic_name', $region);
            })->first();
            // if (!$region) {
            //     return response()->json(['error' => 'Region not found'], 404);
            // }

            $country = Country::where('english_name', $country)
                ->orWhere('arabic_name', $country)
                ->first();
            // if (!$country) {
            //     return response()->json(['error' => 'country not found'], 404);
            // }
            $validatedData = $request->validate([
                // 'street' => 'required|string',
                // 'region' => 'required|string',
                // 'country' => 'required|string',
                // 'description' => 'nullable|string',
                //'work_days' => 'required',
                //'work_days.*' => 'required|string|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Saturday',
            ]);
            $workDaysString = json_encode($request['work_days']);
            
          
            $workplace = Workplace::create([
                'doctor_id' => $doctor->id,
                'street' => $request['street'],
                'region_id' => $region->id,
                'country_id' => $country->id,
                'description' => $request['description'],
                'work_days' => $workDaysString,
            ]);
            $workplace->work_days = json_decode($workplace->work_days);
            $workplace["Region"] =
                [
                    "english name" => $region->english_name,
                    "arabic name" => $region->arabic_name,
                ];
            $workplace["Country"] =
                [
                    "english name" => $country->english_name,
                    "arabic name" => $country->arabic_name,
                ];
            $workplace["Clinic Name"] = $region->english_name;

            return response()->json([
                'message' => 'Clinic added successfully',
                'work place' => $workplace,
            ]);
        // } else {
        //     return response()->json([
        //         'message' => 'check your approval request status',
        //     ]);
        // }
    }


    public function UpdateWorkPlace(Request $request, $id)
    {
        $user = $request->user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }
        $workplace = Workplace::find($id);
        if (!$workplace) {
            return response()->json(['error' => 'Clinic not found'], 404);
        }

        $validatedData = $request->validate([
            'street' => 'required|string',
            'region' => 'required|string',
            'country' => 'required|string',
            'description' => 'nullable|string',
            'work_days' => 'required',
            'work_days.*' => 'required|string|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Saturday',
        ]);

        $workplace->street = $validatedData['street'];
        $workplace->region = $validatedData['region'];
        $workplace->country = $validatedData['country'];
        $workplace->description = $validatedData['description'];
        $workplace->work_days = json_encode($validatedData['work_days']);
        $workplace->save();
        $workplace->work_days = json_decode($workplace->work_days);
        return response()->json([
            'message' => 'Clinic updated successfully',
            'workplace' => $workplace,
        ]);
    }


    public function DestroyWorkPlace(Request $request,$id)
    {
        $user = $request->user();
        $doctor = Doctor::where('user_id', $user->id)->first();
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }
        $workplace = Workplace::find($id);
        if (!$workplace) {
            return response()->json(['error' => 'Clinic not found'], 404);
        }
        $workplace->delete();
        return response()->json(['message' => 'Clinic deleted successfully']);
    }
}
