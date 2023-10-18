<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function register(Request $request){
        $fields=$request->validate([
            'name' => 'required|max:255',
            'username' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|confirmed',
        ]);
        $user=User::create([
            'name'=>$fields['name'],
            'email'=>$fields['email'],
            'username'=>$fields['username'],
            'password'=>bcrypt($fields['password']),
            
        ]);
        $token=$user->createToken('myapptoken')->plainTextToken;
        $response=[
            'user'=>$user,
            'token'=>$token
        ];
        return response($response,201);
    }


    public function login(Request $request){
        $fields=$request->validate([
        
            'email'=>'required|string',
            'password'=>'required|string'
        ]);
        //check email
        $user=User::where('email',$fields['email'])->first();
        //check passowrd
        if(!$user||!Hash::check($fields['password'],$user->password)){
            return response([
                'message'=>'Wrong password'
            ],401);
        }
        $token=$user->createToken('myapptoken')->plainTextToken;
        $response=[
            'user'=>$user,
            'token'=>$token
        ];
        return response($response,201);
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return [
            'message'=>'You have successfully logged out and the token was successfully deleted'];
    }

}