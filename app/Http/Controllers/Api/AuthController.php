<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Emploi;

class AuthController extends Controller
{
  public function register(Request $request)
{
    // Validate the request
    $fields = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|unique:users,email|max:255',
        'password' => 'required|string|confirmed|min:8',
        'role' => 'required|string|in:hr,emploi',
        'department_id' => 'required_if:role,emploi|exists:departments,id', // only for emploi
    ]);

    // Create the user
    $user = User::create([
        'name' => $fields['name'],
        'email' => $fields['email'],
        'password' => Hash::make($fields['password']),
        'role' => $fields['role'],
    ]);

    if ($fields['role'] === 'emploi') {
        $emploi = Emploi::create([
            'user_id' => $user->id,
            'department_id' => $fields['department_id'],
        ]);
    }

    return response()->json([
        'message' => 'User registered successfully',
        'user' => $user,
        'emploi' => $fields['role'] === 'emploi' ? $emploi : null,
    ]);
}

/**
 * @OA\Get(
 *     path="/api/test",
 *     summary="Test endpoint",
 *     tags={"Test"},
 *     @OA\Response(
 *         response=200,
 *         description="Success"
 *     )
 * )
 */
public function test() {
    return response()->json(['message' => 'OK']);
}


   public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // Create token
        $token = $user->createToken('apiToken')->plainTextToken;

        // Load emploi relation if exists
        $emploi = Emploi::where('user_id', $user->id)->first();

        // Return token + user details
        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => [
                'id'         => $user->id,
                'name'       => $user->name,
                'email'      => $user->email,
                'role'       => $user->role,
            ],
            'emploi' => $emploi 
        ]);
    }


    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json(['message'=>'Logged out']);
    }
}
