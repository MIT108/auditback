<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function test(){
        $response = [
            'data' => "test ok"
        ];
        return response($response, 200);
    }



    public function login(Request $request){
        $fields= $request->validate([
           'email' => 'required|string',
           'password' => 'required|string'
       ]);

       //check email
       $user = User::where('email', $fields['email'])->first();

       //check password
       if(!$user || !Hash::check($fields['password'], $user->password)){
           return response([
                'message' =>'Bad credentials',
                'error' =>'Bad credentials'
           ], 422);
       }

       $token = $user->createToken('authenticationToken')->plainTextToken;

       $response = [
           'data' => $user,
           'token' => $token,
           'message' => 'login successful'
       ];

       return response($response, 200);
   }


   public function logout(){

        if (auth()->user()->tokens()->delete()) {
            $response = [
                'message' => 'logout successful'
            ];
            return response($response, 200);
        }else {
            $response = [
                'message' => 'logout error'
            ];
            return response($response, 422);

        }
    }
}
