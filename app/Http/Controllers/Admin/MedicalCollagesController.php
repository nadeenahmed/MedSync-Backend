<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalCollege;
use Illuminate\Http\Request;

class MedicalCollagesController extends Controller
{
    public function index()
    {
        try {
            $colleges = MedicalCollege::all();
            if ($colleges->isEmpty()) {
                $response = [
                    'message' => "there is no colleges"
                ];
                return response()->json($response, 200);
            }
            return response()->json($colleges, 200);
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
            $college = MedicalCollege::create($validatedData);
            return response()->json($college, 201);
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
            $college = MedicalCollege::find($id);
            if (!$college) {
                return response()->json(['message' => 'college not found'], 404);
            }
            return response()->json($college, 200);
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
            $College = MedicalCollege::findOrFail($id);
            if (!$College) {
                return response()->json(['message' => 'college not found'], 404);
            }
            $validatedData = $request->validate([
                'arabic_name' => 'required|string|max:255',
                'english_name' => 'required|string|max:255',
            ]);
            $College->update($validatedData);
            return response()->json($College, 200);
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
            $college = MedicalCollege::findOrFail($id);
            $college->delete();
            return response()->json(['message' => 'college deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            $response = [
                'message' => 'College not found',
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
