<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Specialities;
class SpecialitiesController extends Controller
{
    public function index()
    {
        $specialities = Specialities::all();
        return response()->json($specialities);
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'arabic_name' => 'required|string|max:255',
            'english_name' => 'required|string|max:255',
        ]);
    
        $specialities = Specialities::create($validatedData);
    
        return response()->json($specialities, 201);
    }

    public function show($id)
    {
        $specialities = Specialities::find($id);
        return response()->json($specialities);    }

    public function update(Request $request, $id)
    {
        $specialities = Specialities::findOrFail($id);

        $validatedData = $request->validate([
            'arabic_name' => 'required|string|max:255',
            'english_name' => 'required|string|max:255',
        ]);

        $specialities->update($validatedData);

        return response()->json($specialities, 200);
    }
    public function destroy($id)
    {
        $specialities = Specialities::findOrFail($id);
        $specialities->delete();
        $response = [
            'message' => 'Deleted Successfully',
        ];
        return response()->json($response, 200);
    }
}
