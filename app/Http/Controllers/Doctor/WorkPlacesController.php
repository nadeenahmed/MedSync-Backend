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
    public function index()
    {
        $workplaces = Workplace::all();
        if ($workplaces->isEmpty()) {
            return response()->json(['message' => 'No Clinics Available'], 200);
        }
        foreach ($workplaces as $workplace) {
            $workplace->work_days = json_decode($workplace->work_days);
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
                'street' => 'required|string',
                'region' => 'required|string',
                'country' => 'required|string',
                'description' => 'nullable|string',
                'work_days' => 'required',
                'work_days.*' => 'required|string|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Saturday',
            ]);
            $workplace = Workplace::create([
                'doctor_id' => $doctor->id,
                'street' => $validatedData['street'],
                'region_id' => $region->id,
                'country_id' => $country->id,
                'description' => $validatedData['description'],
                'work_days' => $validatedData['work_days'],
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


    public function DestroyWorkPlace($id)
    {
        $workplace = Workplace::find($id);
        if (!$workplace) {
            return response()->json(['error' => 'Clinic not found'], 404);
        }

        $workplace->delete();

        return response()->json(['message' => 'Clinic deleted successfully']);
    }
}
