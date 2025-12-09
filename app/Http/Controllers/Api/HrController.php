<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stagiaire;
use App\Models\Group;
use App\Models\GroupProgress;
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

 public function assignGroupToEmploi(Request $request, $group_id)
{
    // Validate that emploi_id exists
    $request->validate([
        'emploi_id' => 'required|exists:emplois,id',
        'status' => 'required|in:pending,approved,refused'
    ]);

    // Find the group
    $group = Group::findOrFail($group_id);

    // Assign the emploi to the group
    $group->emploi_id = $request->emploi_id;
    $group->save();

    // Update all stagiaires in this group with new status
    foreach ($group->stagiaires as $stagiaire) {
        $stagiaire->status = $request->status;
        $stagiaire->save(); // triggers status history automatically
    }

    // Optionally, create a note in group_progress
    GroupProgress::create([
        'group_id' => $group->id,
        'note' => $request->status === 'approved' ? 10 : 0, // example: approved = 10 points
        'date' => now(),
    ]);

    return response()->json([
        'group' => $group,
        'stagiaires' => $group->stagiaires,
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
