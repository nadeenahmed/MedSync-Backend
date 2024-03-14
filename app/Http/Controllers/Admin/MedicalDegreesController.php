<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalDegree;
use Illuminate\Http\Request;

class MedicalDegreesController extends Controller
{
    public function index()
    {
        try {
            $degrees = MedicalDegree::all();
            if ($degrees->isEmpty()) {
                $response = [
                    'message' => "there is no medical degrees"
                ];
                return response()->json($response, 200);
            }
            return response()->json($degrees, 200);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Internal Server Error',
                'errors' => 'error : ' . $e,
            ];
            return response()->json($response, 500);
        }
    }

    public function create(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'arabic_name' => 'required|string|max:255',
                'english_name' => 'required|string|max:255',
            ]);
            $degree = MedicalDegree::create($validatedData);
            return response()->json($degree, 201);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Internal Server Error',
                'errors' => 'error : ' . $e,
            ];
            return response()->json($response, 500);
        }
    }

    public function show($id)
    {
        try {
            $degree = MedicalDegree::find($id);
            if (!$degree) {
                return response()->json(['message' => 'medical degree not found'], 404);
            }
            return response()->json($degree, 200);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Internal Server Error',
                'errors' => 'error : ' . $e,
            ];
            return response()->json($response, 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $degree = MedicalDegree::findOrFail($id);
            if (!$degree) {
                return response()->json(['message' => 'medical degree not found'], 404);
            }
            $validatedData = $request->validate([
                'arabic_name' => 'required|string|max:255',
                'english_name' => 'required|string|max:255',
            ]);
            $degree->update($validatedData);
            return response()->json($degree, 200);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Internal Server Error',
                'errors' => 'error : ' . $e,
            ];
            return response()->json($response, 500);
        }
    }

    public function destroy($id)
    {
        try {
            $degree = MedicalDegree::findOrFail($id);
            $degree->delete();
            return response()->json(['message' => 'medical degree deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $response = [
                'message' => 'medical degree not found',
                'errors' => 'error : ' . $e,
            ];
            return response()->json($response, 404);
        } catch (\Exception $e) {
            $response = [
                'message' => 'Internal Server Error',
                'errors' => 'error : ' . $e,
            ];
            return response()->json($response, 500);
        }
    }
}
