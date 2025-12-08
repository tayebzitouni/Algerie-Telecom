<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;





class TestController extends Controller
{
    /**
     * @OA\Get(
     *     path="/test",
     *     summary="Test endpoint",
     *     tags={"Test"},
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     )
     * )
     */
    public function test()
    {
        return response()->json(['message' => 'OK']);
    }
}

