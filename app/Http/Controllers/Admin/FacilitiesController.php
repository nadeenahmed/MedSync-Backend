<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Facilities;
class FacilitiesController extends Controller
{
    public function index()
    {
        $facilities = Facilities::all();
        return response()->json($facilities);
    }

    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);
    
        $facilities = Facilities::create($validatedData);
    
        return response()->json($facilities, 201);
    }

    public function show($id)
    {
        $facilities = Facilities::find($id);
        return response()->json($facilities);    }

    public function update(Request $request, $id)
    {
        $facilities = Facilities::findOrFail($id);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
        ]);

        $facilities->update($validatedData);

        return response()->json($facilities, 200);
    }
    public function destroy($id)
    {
        $facilities = Facilities::findOrFail($id);
        $facilities->delete();
        $response = [
            'message' => 'Deleted Successfully',
        ];
        return response()->json($response, 200);
    }
}
