<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Emploi;
use App\Models\Group;
use App\Models\GroupProgress;
use App\Models\Stagiaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;




class EmploiController extends Controller
{
  public function myStagiaires()
{
    $user = Auth::user();
    $emploi = $user->emploi;

    if (!$emploi) {
        return response()->json([
            'message' => 'No emploi found for this user'
        ], 403);
    }

    $groups = Group::where('emploi_id', $emploi->id)
        ->with([
            'stagiaires',
            'theme',
            'ecole',
            'emploi.user'
        ])
        ->get();

    $response = $groups->map(function ($group) {
        return [
            'group' => [
                'id'         => $group->id,
                'name'       => $group->name,
                'program'    => $group->program,
                'created_at' => $group->created_at,
            ],
            'theme' => $group->theme,
            'ecole' => $group->ecole,
            'employee' => [
                'emploi_id' => $group->emploi->id,
                'user'      => $group->emploi->user,
            ],
            'stagiaires' => $group->stagiaires
        ];
    });

    return response()->json([
        'emploi_id' => $emploi->id,
        'data' => $response
    ]);
}
  public function index()
    {
        return Emploi::with('user', 'department')->get();
    }

   
public function show($id)
{
    $emploi = Emploi::with('user', 'department')->findOrFail($id);

    $authUser = Auth::user(); // use facade instead of helper

    if (!$authUser) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    if (!$emploi->user) {
        return response()->json(['message' => 'This emploi has no linked user'], 404);
    }

    if ($authUser->role === 'emploi' && $authUser->id !== $emploi->user_id) {
        return response()->json(['message' => 'Forbidden'], 403);
    }

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

    // Get the group we want to assign the theme to
    $group = Group::findOrFail($group_id);

    // Check if another group in the SAME ecole already uses this theme
    $alreadyUsed = Group::where('ecole_id', $group->ecole_id)
        ->where('theme_id', $request->theme_id)
        ->where('id', '!=', $group->id) // exclude current group
        ->exists();

    if ($alreadyUsed) {
        return response()->json([
            'message' => 'This theme is already taken by another group in this school'
        ], 422);
    }

    // Assign theme
    $group->theme_id = $request->theme_id;
    $group->save();

    return response()->json([
        'message' => 'Theme assigned successfully',
        'group' => $group
    ]);
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
