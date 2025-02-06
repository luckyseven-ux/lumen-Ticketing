<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Symfony\Component\Mime\Message;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validasi input
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $request->input('email'))->first();
        try{
            if(!$token=JWTAuth::attempt($credentials)){
                return response()->json([
                    'message'=>'wrong email or password'
                ],401);
            }   
            }catch (JWTException $e){
                return response()->json( [
                'message'=>'failed to create token',
                ],500);
            } return response()->json([
                'message'=>'Login Sucess !!',
                'user'=>$user,
                'token'=>$token,
            ],200);
        }
        public function logout()
    {
        try {
            // Invalidate token saat ini
            JWTAuth::invalidate(JWTAuth::getToken());

            return response()->json([
                'message' => 'Logout berhasil.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal logout.',
            ], 500);
        }
    }
     
}