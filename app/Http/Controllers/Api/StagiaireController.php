<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stagiaire;
use App\Models\Group;

class StagiaireController extends Controller
{
    public function index()
    {
        $groups = Group::with(['ecole', 'emploi', 'stagiaires.statusHistory'])->get();

        $result = $groups->map(function ($group) {
            return [
                'group' => [
                    'id' => $group->id,
                    'name' => $group->name,
                    'program' => $group->program,
                    'theme_id' => $group->theme_id,
                    'ecole' => $group->ecole,
                    'emploi' => $group->emploi,
                    'created_at' => $group->created_at,
                    'updated_at' => $group->updated_at,
                ],
                'stagiaires' => $group->stagiaires->map(function ($stagiaire) {
                    return [
                        'id' => $stagiaire->id,
                        'first_name' => $stagiaire->first_name,
                        'last_name' => $stagiaire->last_name,
                        'email' => $stagiaire->email,
                        'phone' => $stagiaire->phone,
                        'city' => $stagiaire->city,
                        'status' => $stagiaire->status,
                        'cv_url' => $stagiaire->cv_path ? asset('storage/' . $stagiaire->cv_path) : null,
                        'student_card_url' => $stagiaire->student_card_path ? asset('storage/' . $stagiaire->student_card_path) : null,
                        'cover_letter_url' => $stagiaire->cover_letter_path ? asset('storage/' . $stagiaire->cover_letter_path) : null,
                        'created_at' => $stagiaire->created_at,
                        'updated_at' => $stagiaire->updated_at,
                    ];
                }),
            ];
        });

        return response()->json($result);
    }

    public function show($id)
    {
        $stagiaire = Stagiaire::with(['group.ecole', 'group.emploi', 'statusHistory'])->findOrFail($id);

        $result = [
            'group' => [
                'id' => $stagiaire->group->id,
                'name' => $stagiaire->group->name,
                'program' => $stagiaire->group->program,
                'theme_id' => $stagiaire->group->theme_id,
                'ecole' => $stagiaire->group->ecole,
                'emploi' => $stagiaire->group->emploi,
                'created_at' => $stagiaire->group->created_at,
                'updated_at' => $stagiaire->group->updated_at,
            ],
            'stagiaire' => [
                'id' => $stagiaire->id,
                'first_name' => $stagiaire->first_name,
                'last_name' => $stagiaire->last_name,
                'email' => $stagiaire->email,
                'phone' => $stagiaire->phone,
                'city' => $stagiaire->city,
                'status' => $stagiaire->status,
                'cv_url' => $stagiaire->cv_path ? asset('storage/' . $stagiaire->cv_path) : null,
                'student_card_url' => $stagiaire->student_card_path ? asset('storage/' . $stagiaire->student_card_path) : null,
                'cover_letter_url' => $stagiaire->cover_letter_path ? asset('storage/' . $stagiaire->cover_letter_path) : null,
                'created_at' => $stagiaire->created_at,
                'updated_at' => $stagiaire->updated_at,
                'statusHistory' => $stagiaire->statusHistory,
            ]
        ];

        return response()->json($result);
    }

public function store(Request $request)
{
    try {

        $request->validate([
            'stagiaires' => 'required|array|min:1',
            'stagiaires.*.first_name' => 'required|string',
            'stagiaires.*.last_name' => 'required|string',
            'stagiaires.*.email' => 'nullable|email',
            'stagiaires.*.phone' => 'nullable|string',
            'stagiaires.*.city' => 'nullable|string',
            'ecole_id' => 'required|exists:ecoles,id',
            'emploi_id' => 'nullable|exists:emplois,id',
        ]);

        $group = Group::create([
            'name' => 'Group ' . now()->format('Y-m-d H:i:s'),
            'theme_id' => null,
            'ecole_id' => $request->ecole_id,
            'emploi_id' => $request->emploi_id ?? null,
        ]);

        $createdStagiaires = [];

        foreach ($request->stagiaires as $index => $s) {

            $data = [
                'first_name' => $s['first_name'],
                'last_name' => $s['last_name'],
                'email' => $s['email'] ?? null,
                'phone' => $s['phone'] ?? null,
                'city' => $s['city'] ?? null,
                'group_id' => $group->id,
                'status' => 'pending',
            ];

            $files = ['cv', 'student_card', 'cover_letter'];

            foreach ($files as $file) {
                if ($request->hasFile("$file.$index")) {
                    $uploaded = $request->file("$file.$index");
                    $data[$file . '_path'] =
                        $uploaded->store("documents/$file", 'public');
                }
            }

            $stagiaire = Stagiaire::create($data);

            // Add full URLs to return to frontend
            $stagiaire->cv_url = $stagiaire->cv_path
                ? url('storage/' . $stagiaire->cv_path)
                : null;

            $stagiaire->student_card_url = $stagiaire->student_card_path
                ? url('storage/' . $stagiaire->student_card_path)
                : null;

            $stagiaire->cover_letter_url = $stagiaire->cover_letter_path
                ? url('storage/' . $stagiaire->cover_letter_path)
                : null;

            $createdStagiaires[] = $stagiaire;
        }

        return response()->json([
            'success' => true,
            'group' => $group,
            'stagiaires' => $createdStagiaires,
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'errors' => $e->errors(),
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
}




    public function update(Request $request, $id)
    {
        $stagiaire = Stagiaire::with('group')->findOrFail($id);
        $group = $stagiaire->group;

        $request->validate([
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:stagiaires,email,' . $stagiaire->id,
            'phone' => 'sometimes|string|max:20',
            'city' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:pending,approved,refused',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'student_card' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'cover_letter' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'group.name' => 'sometimes|string|max:255',
            'group.theme_id' => 'sometimes|nullable|exists:themes,id',
            'group.ecole_id' => 'sometimes|exists:ecoles,id',
            'group.emploi_id' => 'sometimes|nullable|exists:emplois,id',
        ]);

        $stagiaireData = $request->only(['first_name', 'last_name', 'email', 'phone', 'city', 'status']);

        if ($request->hasFile('cv')) {
            $stagiaireData['cv_path'] = $request->file('cv')->store('documents/cv', 'public');
        }
        if ($request->hasFile('student_card')) {
            $stagiaireData['student_card_path'] = $request->file('student_card')->store('documents/student_cards', 'public');
        }
        if ($request->hasFile('cover_letter')) {
            $stagiaireData['cover_letter_path'] = $request->file('cover_letter')->store('documents/cover_letters', 'public');
        }

        $stagiaire->update($stagiaireData);

        if ($request->has('group') && $group) {
            $group->update($request->input('group'));
        }

        return response()->json([
            'stagiaire' => $stagiaire,
            'group' => $group,
        ]);
    }

    public function destroy($id)
    {
        $stagiaire = Stagiaire::findOrFail($id);
        $group = $stagiaire->group;

        $stagiaire->delete();

        if ($group && $group->stagiaires()->count() === 0) {
            $group->delete();
        }

        return response()->json(['message' => 'Stagiaire deleted successfully']);
    }
}
