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

 public function assignStagiaire(Request $request, $stagiaire_id)
    {
        $request->validate(['emploi_id'=>'required|exists:emplois,id']);
        $stagiaire = Stagiaire::findOrFail($stagiaire_id);
        $stagiaire->emploi_id = $request->emploi_id;
        $stagiaire->save();
        return response()->json($stagiaire);
    }

    public function allHR()
    {
        $hrs = User::where('role', 'hr')->get();
        return response()->json($hrs);
    }
   
}
