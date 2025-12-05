<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stagiaire;


class StagiaireController extends Controller
{
  public function index()
{
    $groups = \App\Models\Group::with(['ecole', 'emploi', 'stagiaires.statusHistory'])->get();

    // Transform the data
    $result = $groups->map(function ($group) {
        return [
            'group' => [
                'id' => $group->id,
                'name' => $group->name,
                'theme_id' => $group->theme_id,
                'ecole' => $group->ecole,    // ecole info
                'emploi' => $group->emploi,  // emploi info
                'created_at' => $group->created_at,
                'updated_at' => $group->updated_at,
            ],
            'stagiaires' => $group->stagiaires->map(function ($stagiaire) {
                return [
                    'id' => $stagiaire->id,
                    'name' => $stagiaire->name,
                    'email' => $stagiaire->email,
                    'phone' => $stagiaire->phone,
                    'status' => $stagiaire->status,
                    'created_at' => $stagiaire->created_at,
                    'updated_at' => $stagiaire->updated_at,
                ];
            }),
        ];
    });

    return response()->json($result);
}



   public function store(Request $request)
{
    $request->validate([
        'stagiaires' => 'required|array|min:1',
        'stagiaires.*.name' => 'required|string',
        'stagiaires.*.email' => 'nullable|email',
        'stagiaires.*.phone' => 'nullable|string',
        'ecole_id' => 'required|exists:ecoles,id',
        'emploi_id' => 'nullable|exists:emplois,id'
    ]);

    $group = \App\Models\Group::create([
        'name' => 'Group for '.now()->format('Y-m-d H:i:s'),
        'theme_id' => null,
        'ecole_id' => $request->ecole_id,
        'emploi_id' => $request->emploi_id ?? null
    ]);

    $createdStagiaires = [];

    foreach ($request->stagiaires as $s) {
        $stagiaire = \App\Models\Stagiaire::create([
            'name' => $s['name'],
            'email' => $s['email'] ?? null,
            'phone' => $s['phone'] ?? null,
            'ecole_id' => $request->ecole_id,
            'emploi_id' => $request->emploi_id ?? null,
            'group_id' => $group->id,
            'status' => 'pending'
        ]);
        $createdStagiaires[] = $stagiaire;
    }

    return response()->json([
        'group' => $group,
        'stagiaires' => $createdStagiaires
    ]);
}



  public function show($id)
{
    $stagiaire = \App\Models\Stagiaire::with(['group.ecole', 'group.emploi', 'statusHistory'])->findOrFail($id);

    $result = [
        'group' => [
            'id' => $stagiaire->group->id,
            'name' => $stagiaire->group->name,
            'theme_id' => $stagiaire->group->theme_id,
            'ecole' => $stagiaire->group->ecole,
            'emploi' => $stagiaire->group->emploi,
            'created_at' => $stagiaire->group->created_at,
            'updated_at' => $stagiaire->group->updated_at,
        ],
        'stagiaire' => [
            'id' => $stagiaire->id,
            'name' => $stagiaire->name,
            'email' => $stagiaire->email,
            'phone' => $stagiaire->phone,
            'status' => $stagiaire->status,
            'created_at' => $stagiaire->created_at,
            'updated_at' => $stagiaire->updated_at,
            'statusHistory' => $stagiaire->statusHistory,
        ]
    ];

    return response()->json($result);
}


    public function update(Request $request, $id)
{
    $stagiaire = \App\Models\Stagiaire::with('group')->findOrFail($id);
    $group = $stagiaire->group;

    // Validate request
    $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:stagiaires,email,'.$stagiaire->id,
        'phone' => 'sometimes|string|max:20',
        'status' => 'sometimes|in:pending,approved,refused',
        'group.name' => 'sometimes|string|max:255',
        'group.theme_id' => 'sometimes|nullable|exists:themes,id',
        'group.ecole_id' => 'sometimes|exists:ecoles,id',
        'group.emploi_id' => 'sometimes|nullable|exists:emplois,id',
    ]);

    // Update stagiaire fields
    $stagiaire->update($request->only(['name', 'email', 'phone', 'status']));

    // Update group fields if provided
    if ($request->has('group') && $group) {
        $groupData = $request->input('group');
        $group->update($groupData);
    }

    return response()->json([
        'stagiaire' => $stagiaire,
        'group' => $group
    ]);
}


   public function destroy($id)
{
    $stagiaire = \App\Models\Stagiaire::findOrFail($id);
    $group = $stagiaire->group;

    // Delete stagiaire
    $stagiaire->delete();

    // If the group has no other stagiaires, delete it too
    if ($group && $group->stagiaires()->count() === 0) {
        $group->delete();
    }

    return response()->json(['message' => 'Stagiaire deleted successfully']);
}


  
   
}
