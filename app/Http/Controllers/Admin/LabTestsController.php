<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LabTest;
class LabTestsController extends Controller
{
    public function index()
    {
        $labTests = LabTest::all();
        return response()->json($labTests);
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'arabic_name' => 'required|string|max:255',
            'english_name' => 'required|string|max:255',
        ]);
    
        $labTests = LabTest::create($validatedData);
    
        return response()->json($labTests, 201);
    }

    public function show($id)
    {
        $labTests = LabTest::find($id);
        return response()->json($labTests);    }

    public function update(Request $request, $id)
    {
        $labTests = LabTest::findOrFail($id);

        $validatedData = $request->validate([
            'arabic_name' => 'required|string|max:255',
            'english_name' => 'required|string|max:255',
        ]);

        $labTests->update($validatedData);

        return response()->json($labTests, 200);
    }
    public function destroy($id)
    {
        $labTests = LabTest::findOrFail($id);
        $labTests->delete();
        $response = [
            'message' => 'Deleted Successfully',
        ];
        return response()->json($response, 200);
    }
}
