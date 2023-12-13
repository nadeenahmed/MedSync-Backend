<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drugs;
use Illuminate\Http\Request;

class DrugController extends Controller
{
    public function index()
    {
        $drugs = Drugs::all();
        return response()->json($drugs);
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        $drugs = Drugs::create($validatedData);
    
        return response()->json($drugs, 201);
    }

    public function show($id)
    {
        $drugs = Drugs::find($id);
        return response()->json($drugs);    }

    public function update(Request $request, $id)
    {
        $drugs = Drugs::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $drugs->update($validatedData);

        return response()->json($drugs, 200);
    }
    public function destroy($id)
    {
        $drugs = Drugs::findOrFail($id);
        $drugs->delete();
        $response = [
            'message' => 'Deleted Successfully',
        ];
        return response()->json($response, 200);
    }
}
