<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Stagiaire;
use App\Models\Group;
use App\Models\GroupProgress;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

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


    // public function changeStatus(Request $request, $id)
    // {
    //     $request->validate(['status'=>'required|in:pending,approved,refused']);
    //     $stagiaire = Stagiaire::findOrFail($id);
    //     $stagiaire->status = $request->status;
    //     $stagiaire->save();
    //     return response()->json($stagiaire);
    // }

 public function changeStatus(Request $request, $group_id)
{
    // Validate the request
    $request->validate([
        'status' => 'required|in:pending,approved,refused',
        'emploi_id' => 'required_if:status,approved|exists:emplois,id',
        'note' => 'required_if:status,refused|string|max:500'
    ]);

    // Get the group
    $group = Group::findOrFail($group_id);

    /**
     * ✔️ If APPROVED → assign emploi_id
     */
    if ($request->status === 'approved') {
        $group->emploi_id = $request->emploi_id;
    }

    /**
     * ✔️ If REFUSED → save note and send email
     */
    if ($request->status === 'refused') {
        // Save refusal note

        // Send email to all stagiaires
        foreach ($group->stagiaires as $stagiaire) {
           Mail::to($stagiaire->email)->send(new \App\Mail\GroupRefusedMail(
    $group,
    $request->note,
    $stagiaire
));

        }
    }

    $group->save();

    /**
     * ✔️ Update status of all stagiaires
     */
    foreach ($group->stagiaires as $stagiaire) {
        $stagiaire->status = $request->status;
        $stagiaire->save(); // triggers history if you use model events
    }

    /**
     * ✔️ Create group progress record
     */
    GroupProgress::create([
        'group_id' => $group->id,
        'note' => $request->status === 'approved' ? 10 : 0,
        'date' => now(),
    ]);

    return response()->json([
        'message' => "Status updated successfully",
        'group' => $group,
        'stagiaires' => $group->stagiaires
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
