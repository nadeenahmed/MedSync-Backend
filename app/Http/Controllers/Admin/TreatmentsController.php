<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Treatments;
class TreatmentsController extends Controller
{
    public function index()
    {
        $treatments = Treatments::all();
        return response()->json($treatments);
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        $treatments = Treatments::create($validatedData);
    
        return response()->json($treatments, 201);
    }

    public function show($id)
    {
        $treatments = Treatments::find($id);
        return response()->json($treatments);    }

    public function update(Request $request, $id)
    {
        $treatments = Treatments::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $treatments->update($validatedData);

        return response()->json($treatments, 200);
    }
    public function destroy($id)
    {
        $treatments = Treatments::findOrFail($id);
        $treatments->delete();
        $response = [
            'message' => 'Deleted Successfully',
        ];
        return response()->json($response, 200);
    }
}
