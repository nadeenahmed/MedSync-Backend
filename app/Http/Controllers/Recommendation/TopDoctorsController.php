<?php

namespace App\Http\Controllers\Recommendation;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class TopDoctorsController extends Controller
{
    public function getTopDoctors()
    {
        $doctors = Doctor::where('years_of_experience', '>=', 5)
            ->where('medical_degree_id', '>=', 5)
            ->orderBy('years_of_experience', 'desc')
            ->orderBy('medical_degree_id', 'desc')
            ->get();

        return response()->json(['top_doctors' => $doctors]);
    }
}
