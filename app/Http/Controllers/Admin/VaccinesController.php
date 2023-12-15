<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vaccines;
class VaccinesController extends Controller
{
    public function index()
    {
        $vaccines = Vaccines::all();
        return response()->json($vaccines);
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'arabic_name' => 'required|string|max:255',
            'english_name' => 'required|string|max:255',
        ]);
    
        $vaccines = Vaccines::create($validatedData);
    
        return response()->json($vaccines, 201);
    }

    public function show($id)
    {
        $vaccines = Vaccines::find($id);
        return response()->json($vaccines);    }

    public function update(Request $request, $id)
    {
        $vaccines = Vaccines::findOrFail($id);

        $validatedData = $request->validate([
            'arabic_name' => 'required|string|max:255',
            'english_name' => 'required|string|max:255',
        ]);

        $vaccines->update($validatedData);

        return response()->json($vaccines, 200);
    }
    public function destroy($id)
    {
        $vaccines = Vaccines::findOrFail($id);
        $vaccines->delete();
        $response = [
            'message' => 'Deleted Successfully',
        ];
        return response()->json($response, 200);
    }
}
