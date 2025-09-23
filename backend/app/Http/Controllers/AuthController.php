<?php

namespace App\Http\Controllers;

use App\Models\User as ModelsUser;

use Illuminate\Http\Request;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Symfony\Component\HttpFoundation\Response;
use User;

class AuthController extends Controller
{
   public function register(Request $request){
    $request->validate([
        'name'=>'required',
        'email'=>'required|email|unique:users',
        'password'=>'required|confirmed'
    ]);
    $user = new ModelsUser();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password =Hash::make($request->password);
    $user->save();
    
    return response($user, Response::HTTP_CREATED);
    
   }

   public function login(Request $request){
    $credenciales = $request->validate([
        'email'=>['required','email'],
        'password'=>['required']
    ]);

    if(FacadesAuth::attempt($credenciales)){
        $user = FacadesAuth::user();
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('cookie_token', $token, 60 * 24);
        return response(["token"=>$token], Response::HTTP_OK)->withoutCookie($cookie);
    }else{
        return response(["message"=>"Credenciales Invalidas"],Response::HTTP_UNAUTHORIZED);
    }
    
   }

   public function logOut(Request $request){
    $token = $request->user()->currentAccessToken()->delete();

    $cookie = cookie()->forget('cookie_token');

    return response()->json([
        'message' => 'Logout exitoso'
    ], Response::HTTP_OK)->withCookie($cookie);
   }


}