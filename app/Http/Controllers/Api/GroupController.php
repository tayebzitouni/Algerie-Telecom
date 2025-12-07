<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function index()
    {
        return Group::with(['theme','ecole','emploi','stagiaires','progress'])->get();
    }

    public function show($id)
    {
        return Group::with(['theme','ecole','emploi','stagiaires','progress'])->findOrFail($id);
    }
}
