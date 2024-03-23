<?php

namespace App\Http\Controllers\MedicalHistory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MedicalHistory;

class DeleteController extends Controller
{
    
    public function deleteMedicalRecord($id)
    {
        $medicalRecord = MedicalHistory::find($id);
        if (!$medicalRecord) {
            return response()->json(['message' => 'Medical record not found'], 404);
        }
        $medicalRecord->delete();
        return response()->json(['message' => 'Medical record deleted successfully']);
    }

}
