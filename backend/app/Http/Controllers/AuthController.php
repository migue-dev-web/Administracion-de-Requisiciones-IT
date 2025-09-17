<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    private $file = 'users.json';

    private function readUsers()
    {
        if (!Storage::exists($this->file)) {
            Storage::put($this->file, json_encode([]));
        }
        return json_decode(Storage::get($this->file), true);
    }

    private function writeUsers($users)
    {
        Storage::put($this->file, json_encode($users, JSON_PRETTY_PRINT));
    }

    public function register(Request $request)
    {
        $users = $this->readUsers();

      
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:4'
        ]);

        
        foreach ($users as $user) {
            if ($user['email'] === $request->email) {
                return response()->json(['error' => 'Usuario ya registrado'], 400);
            }
        }

        $newUser = [
            'email' => $request->email,
            'password' => bcrypt($request->password) 
        ];

        $users[] = $newUser;
        $this->writeUsers($users);

        return response()->json(['message' => 'Registro exitoso']);
    }

    public function login(Request $request)
    {
        $users = $this->readUsers();

        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        foreach ($users as $user) {
            if ($user['email'] === $request->email && password_verify($request->password, $user['password'])) {
                return response()->json(['message' => 'Login exitoso']);
            }
        }

        return response()->json(['error' => 'Credenciales inválidas'], 401);
    }
}