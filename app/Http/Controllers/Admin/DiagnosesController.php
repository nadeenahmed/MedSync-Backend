<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Diagnoses;
class DiagnosesController extends Controller
{
    public function index()
    {
        $diagnoses = Diagnoses::all();
        return response()->json($diagnoses);
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        $diagnoses = Diagnoses::create($validatedData);
    
        return response()->json($diagnoses, 201);
    }

    public function show($id)
    {
        $diagnoses = Diagnoses::find($id);
        return response()->json($diagnoses);    }

    public function update(Request $request, $id)
    {
        $diagnoses = Diagnoses::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $diagnoses->update($validatedData);

        return response()->json($diagnoses, 200);
    }
    public function destroy($id)
    {
        $diagnoses = Diagnoses::findOrFail($id);
        $diagnoses->delete();
        $response = [
            'message' => 'Deleted Successfully',
        ];
        return response()->json($response, 200);
    }
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('ids');

        Diagnoses::whereIn('id', $ids)->delete();
        $response = [
            'message' => 'Selected records deleted successfully',
        ];
        return response()->json($response, 200);
    }
}
