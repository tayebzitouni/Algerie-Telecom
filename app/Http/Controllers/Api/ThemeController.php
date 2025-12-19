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
        $themes = Theme::where('employee_id', Auth::id())->get();
        return response()->json($themes);
    }

    // GET a specific theme by id (only if belongs to employee)
    public function show($id)
    {
        $theme = Theme::where('id', $id)->where('employee_id', Auth::id())->firstOrFail();
        return response()->json($theme);
    }

    // CREATE a theme for authenticated employee
    public function store(Request $request)
    {
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

        $data['employee_id'] = Auth::id();

        $theme = Theme::create($data);

        return response()->json($theme, 201);
    }

    // UPDATE a theme (only if belongs to employee)
    public function update(Request $request, $id)
    {
        $theme = Theme::where('id', $id)->where('employee_id', Auth::id())->firstOrFail();

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
