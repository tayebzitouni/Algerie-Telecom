<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Emploi;
use App\Models\GroupProgress;
use App\Models\Stagiaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class EmploiController extends Controller
{
    public function myStagiaires(Request $request)
    {
        $emploi_id = $request->user()->id; // or map user to emploi_id
        return Stagiaire::where('emploi_id',$emploi_id)->with('group')->get();
    }
    
  public function index()
    {
        return Emploi::with('user', 'department')->get();
    }

   
    public function show($id)
    {
        $emploi = Emploi::with('user', 'department')->findOrFail($id);
        return response()->json($emploi);
    }


    public function addNote(Request $request)
    {
        $request->validate([
            'group_id'=>'required|exists:groups,id',
            
            'note'=>'required|integer',
            'date'=>'required|date'
        ]);

        $progress = GroupProgress::create($request->all());
        return response()->json($progress);
    }


public function assignTheme(Request $request, $group_id)
{
    $request->validate([
        'theme_id' => 'required|exists:themes,id',
    ]);

    $group = \App\Models\Group::findOrFail($group_id);

    $themeCount = \App\Models\Group::where('ecole_id', $group->ecole_id)
        ->where('theme_id', $request->theme_id)
        ->count();
    if ($themeCount >= 2) {
        return response()->json([
            'message' => 'This theme is already used by 2 groups in the school'
        ], 400);
    }

    $group->theme_id = $request->theme_id;
    $group->save();

    return response()->json($group);
}


   

   
    /**
     * Update the specified resource in storage.
     */
  public function update(Request $request, $id)
{
    $emploi = Emploi::with('user')->findOrFail($id);
    $user = $emploi->user;

    // Validate inputs
    $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:users,email,' . $user->id,
        'password' => 'sometimes|string|confirmed|min:8',
        'department_id' => 'sometimes|exists:departments,id',
    ]);

    // Update linked user
    if ($request->has('name')) {
        $user->name = $request->name;
    }
    if ($request->has('email')) {
        $user->email = $request->email;
    }
    if ($request->has('password')) {
        $user->password = Hash::make($request->password);
    }
    $user->save();

    // Update emploi department
    if ($request->has('department_id')) {
        $emploi->department_id = $request->department_id;
        $emploi->save();
    }

    return response()->json($emploi->load('user', 'department'));
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $emploi = Emploi::findOrFail($id);
        $emploi->user->delete(); // this also deletes emploi if FK cascade is set
        return response()->json(['message'=>'Emploi deleted']);
    }
}
