<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller {
    public function __construct(){
        $this->middleware('auth:api', ['except' => ['login','register','logout']]);
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

    public function login(Request $request)
    {
        $pairs = $request->header('Authorization');
        $pairs = substr($pairs, 6);
        $pairs = base64_decode($pairs);
        $pairs = explode(":",$pairs);
    
        if (!$token = auth()->attempt(['email' => $pairs[0], 'password' => $pairs[1]])) {
            return response()->json(['message' => 'email atau password salah'], 401);
        }
    
        return $this->respondWithToken($token);
    }

    public function me() {
        return response()->json(auth()->user());
    }

    public function refresh() {
        return $this->respondWithToken(auth()->refresh());
    }

    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'Berhasil log out!']);
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