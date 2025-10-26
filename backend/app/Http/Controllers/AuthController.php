<?php

namespace App\Http\Controllers;

use App\Models\User as ModelsUser;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Symfony\Component\HttpFoundation\Response;
use User;

class AuthController extends Controller
{
   public function Reg(Request $request){
    $request->validate([
        'name'=>'required',
        'email'=>'required|email|unique:users',
        'password'=>'required|confirmed'
    ]);
    $user = new ModelsUser();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password =Hash::make($request->password);
    $user->role = $request->role ?? 'user';
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

        $roleNumber = match ($user->role) {
            'user' => 0,
            'usuario' => 0,
            'tecnico' => 1,
            'admin' => 2,
            default => 0,
        };

        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('cookie_token', $token, 60 * 24);
         return response()->json([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $roleNumber,
            ],
        ], Response::HTTP_OK)->withoutCookie($cookie);
    }else{
        return response(["message"=>"Credenciales Invalidas"],Response::HTTP_UNAUTHORIZED);
    }
    
   }
   public function obtenerUsuarios()
{
    // Selecciona solo los campos necesarios para no exponer datos sensibles
    $usuarios = ModelsUser::select('id', 'name', 'email', 'role')->get();

    return response()->json($usuarios, Response::HTTP_OK);
}

   public function updateRole(Request $request, $id)
{
    $request->validate([
        'role' => 'required|in:admin,tecnico,usuario',
    ]);

    $user = ModelsUser::findOrFail($id);
    $user->role = $request->role;
    $user->save();

    return response()->json(['message' => 'Rol actualizado correctamente', 'user' => $user]);
}




}