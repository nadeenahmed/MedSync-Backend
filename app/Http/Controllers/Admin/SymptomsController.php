<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Symptoms;

class SymptomsController extends Controller
{
    public function index()
    {
        $symptoms = Symptoms::all();
        return response()->json($symptoms);
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);
    
        $symptoms = Symptoms::create($validatedData);
    
        return response()->json($symptoms, 201);
    }

    public function show($id)
    {
        $symptoms = Symptoms::find($id);
        return response()->json($symptoms);    }

    public function update(Request $request, $id)
    {
        $symptoms = Symptoms::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
        ]);

        $symptoms->update($validatedData);

        return response()->json($symptoms, 200);
    }
    public function destroy($id)
    {
        $symptoms = Symptoms::findOrFail($id);
        $symptoms->delete();
        $response = [
            'message' => 'Deleted Successfully',
        ];
        return response()->json($response, 200);
    }
}
