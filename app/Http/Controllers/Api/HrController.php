<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stagiaire;
use App\Models\Group;
use App\Models\User;

class HrController extends Controller
{
    public function stagiaires()
    {
         return Stagiaire::with(['ecole','group','statusHistory'])->get();
        
    }

    public function stagiere($id)
    {
         return Stagiaire::with(['ecole','group','statusHistory'])->findOrFail($id);
        
    }


    public function changeStatus(Request $request, $id)
    {
        $request->validate(['status'=>'required|in:pending,approved,refused']);
        $stagiaire = Stagiaire::findOrFail($id);
        $stagiaire->status = $request->status;
        $stagiaire->save();
        return response()->json($stagiaire);
    }

 public function assignGroupToEmploi(Request $request)
{
    $request->validate([
        'group_id'  => 'required|exists:groups_table,id',
        'emploi_id' => 'required|exists:emplois,id',
        'status'    => 'sometimes|in:pending,approved,refused' // optional, default to approved
    ]);

    $group = \App\Models\Group::with('stagiaires')->findOrFail($request->group_id);

    $status = $request->status ?? 'approved';
    $updatedStagiaires = [];

    foreach ($group->stagiaires as $stagiaire) {
        $stagiaire->emploi_id = $request->emploi_id;
        $stagiaire->status = $status;
        $stagiaire->save(); // this will also trigger statusHistory automatically
        $updatedStagiaires[] = $stagiaire;
    }

    // Add a note in group_progress automatically
    \App\Models\GroupProgress::create([
        'group_id' => $group->id,
        'note'     => $status === 'approved' ? 1 : ($status === 'refused' ? 0 : null), 
        'date'     => now()->toDateString()
    ]);

    return response()->json([
        'message' => 'Group assigned to emploi successfully',
        'group' => $group,
        'stagiaires' => $updatedStagiaires
    ]);
}



    public function allHR()
    {
        $hrs = User::where('role', 'hr')->get();
        return response()->json($hrs);
    }

     public function HByid($id)
    {
        $hr = User::where('role', 'hr')->findOrFail($id);
        return response()->json($hr);
    }

   
}
