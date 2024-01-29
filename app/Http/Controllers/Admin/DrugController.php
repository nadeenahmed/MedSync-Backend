<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Medications;
use Illuminate\Http\Request;

class MedicationController extends Controller
{
    public function index()
    {
        $medications = Medications::all();
        return response()->json($medications);
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        $medications = Medications::create($validatedData);
    
        return response()->json($medications, 201);
    }

    public function show($id)
    {
        $medications = Medications::find($id);
        return response()->json($medications);    }

    public function update(Request $request, $id)
    {
        $medications = Medications::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $medications->update($validatedData);

        return response()->json($medications, 200);
    }
    public function destroy($id)
    {
        $medications = Medications::findOrFail($id);
        $medications->delete();
        $response = [
            'message' => 'Deleted Successfully',
        ];
        return response()->json($response, 200);
    }
}
