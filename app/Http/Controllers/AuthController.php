<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller {
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }
    public function register(Request $request){
        //Tambahkan validasi
        $this->validate($request,[
            'email' => 'required|email|unique:user',
            'name' => 'required|string|max:255',
            'password' => 'required|min:8|max:64',
        ]);
        $user = new User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = Hash::make( $request->password);
        $user->save();
        return response()->json(["message" => "User Berhasil Dibuat"]);

    }



//Login dengan basic autentifikasi
    // public function login(Request $request)
    // {
        
    //     $pairs = $request->header('Authorization');
    //     if (!$pairs || !str_starts_with($pairs, 'Basic ')) {
    //         return response()->json(["message" => "Header Authorization tidak valid"], 400);
    //     }

    //     $pairs = substr($pairs, 6);
    //     $pairs = base64_decode($pairs);
    //     $pairs = explode(":", $pairs);

    //     if (count($pairs) !== 2) {
    //         return response()->json(["message" => "Format Authorization tidak valid"], 400);
    //     }

    //     $user = User::where('email', $pairs[0])->first();

    //     if (!$user) {
    //         return response()->json(["message" => "Email atau password salah"], 401);
    //     }

    //     if (Hash::check($pairs[1], $user->password)) {
    //         return response()->json(["message" => "Login berhasil"]);
    //     } else {
    //         return response()->json(["message" => "Email atau password salah"], 401);
    //     }
    // }

    //Login dengan JWT AUTH

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
    
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        return $this->respondWithToken($token);
    }
    
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    
}