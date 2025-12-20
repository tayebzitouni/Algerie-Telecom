<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    // GET all departments
    public function index()
{
    $departments = Department::with(['emplois'])->get();
    return response()->json($departments);
}


    // STORE new department
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            
        ]);

        $department = Department::create($request->all());

        return response()->json($department, 201);
    }

    // SHOW one department
    public function show($id)
    {
        return response()->json(
            Department::with(['emplois'])->findOrFail($id)
        );
    }

    // UPDATE department
    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);

        $department->update($request->only([
            'name',
        ]));

        return response()->json($department);
    }

    // DELETE department
    public function destroy($id)
    {
        Department::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Department deleted'
        ]);
    }
}
