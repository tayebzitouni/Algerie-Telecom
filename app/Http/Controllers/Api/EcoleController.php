<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Ecole;
use Illuminate\Http\Request;

class EcoleController extends Controller
{
    public function index()
    {
        return Ecole::all();
    }

    public function show($id)
    {
        return Ecole::findOrFail($id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
            'email' => 'nullable|email|unique:ecoles,email',
        ]);

        $ecole = Ecole::create($request->all());

        return response()->json($ecole, 201);
    }

    public function update(Request $request, $id)
    {
        $ecole = Ecole::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:50',
            'email' => 'sometimes|email|unique:ecoles,email,'.$ecole->id,
        ]);

        $ecole->update($request->all());

        return response()->json($ecole);
    }

    public function destroy($id)
    {
        $ecole = Ecole::findOrFail($id);
        $ecole->delete();

        return response()->json(['message' => 'Ecole deleted successfully']);
    }
}
