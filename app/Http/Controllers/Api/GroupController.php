<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\GroupProgress;
use Illuminate\Http\Request;

class GroupController extends Controller
{
     public function show($id)
    {
        return Group::with(['theme','ecole','emploi','stagiaires','progress'])->findOrFail($id);
    }

   public function index()
    {
        return Group::with(['theme','ecole','emploi','stagiaires','progress'])->get();
    }


   
  
}
