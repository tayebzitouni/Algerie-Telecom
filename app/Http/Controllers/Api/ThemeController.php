<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class ThemeController extends Controller
{
// GET all themes of authenticated employee
public function index()
{
    $user = Auth::user(); // get the authenticated user
    $emploi = $user->emploi; // get the related emploi

    if (!$emploi) {
        return response()->json(['error' => 'No emploi found for this user'], 400);
    }

    $themes = Theme::where('employee_id', $emploi->id)->get();

    return response()->json($themes);
}


   // GET a specific theme by id (only if belongs to employee)
public function show($id)
{
    $user = Auth::user();       // get authenticated user
    $emploi = $user->emploi;    // get the related emploi

    if (!$emploi) {
        return response()->json(['error' => 'No emploi found for this user'], 400);
    }

    $theme = Theme::where('id', $id)
                  ->where('employee_id', $emploi->id)
                  ->firstOrFail();

    return response()->json($theme);
}





public function store(Request $request)
    {
        $request->validate([
            'name'                => 'required|string|max:255',
            'category'            => 'required|string|max:255',
            'duration'            => 'required|integer|min:1',
            'description'         => 'required|string',
            'requirements'        => 'required|string',
            'learning_objectives' => 'required|string',
            'max_capacity'        => 'required|integer|min:1',
            'difficulty_level'    => 'required|in:beginner,intermediate,advanced',
            'documentation'       => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $emploi = Auth::user()->emploi;

        if (!$emploi) {
            return response()->json(['error' => 'No emploi found for this user'], 400);
        }

        $data = $request->only([
            'name', 'category', 'duration', 'description', 
            'requirements', 'learning_objectives', 
            'max_capacity', 'difficulty_level'
        ]);

        if ($request->hasFile('documentation')) {
            $data['documentation_path'] = $request->file('documentation')
                                                 ->store('themes', 'public');
        }

        // Set employee_id from the emploi
        $data['employee_id'] = $emploi->id;

        $theme = Theme::create($data);

        return response()->json($theme, 201);
    }

    // UPDATE a theme
    public function update(Request $request, $id)
    {
        $emploi = Auth::user()->emploi;

        if (!$emploi) {
            return response()->json(['error' => 'No emploi found for this user'], 400);
        }

        $theme = Theme::where('id', $id)
                      ->where('employee_id', $emploi->id)
                      ->firstOrFail();

        $data = $request->validate([
            'name'                => 'required|string|max:255',
            'category'            => 'required|string|max:255',
            'duration'            => 'required|integer|min:1',
            'description'         => 'required|string',
            'requirements'        => 'required|string',
            'learning_objectives' => 'required|string',
            'max_capacity'        => 'required|integer|min:1',
            'difficulty_level'    => 'required|in:beginner,intermediate,advanced',
            'documentation'       => 'nullable|file|mimes:pdf,doc,docx|max:10240',
        ]);

        if ($request->hasFile('documentation')) {
            $data['documentation_path'] = $request->file('documentation')
                                                 ->store('themes', 'public');
        }

        $theme->update($data);

        return response()->json($theme);
    }


    // DELETE a theme (only if belongs to employee)
    public function destroy($id)
    {
        $theme = Theme::where('id', $id)->where('employee_id', Auth::id())->firstOrFail();
        $theme->delete();

        return response()->json(['message' => 'Theme deleted']);
    }
}
