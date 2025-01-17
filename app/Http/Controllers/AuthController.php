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

        $verificationHash = base64_encode($request->name.'_'.$request->email);
        // TO DO: send email verification and attach verification link!

        return response()->json([
            "message" => "User Berhasil Dibuat",
            "verification_link" => url('/email/verify/'.$user->id.'/'.$verificationHash)
        ]);

    }

    public function login(Request $request)
    {
        $pairs = $request->header('Authorization');
        $pairs = substr($pairs, 6);
        $pairs = base64_decode($pairs);
        $pairs = explode(":",$pairs);

        $email = $pairs[0];
        $password = $pairs[1];
        if (! $token = auth()->attempt(['email' => $email, 'password' => $password])) {
            return response()->json(["message" => "email atau password salah"], 401);
        }
    
        $user = User::where('email', $email)->first();
        if (! $user->hasVerifiedEmail()) {
            return response()->json([
                "message" => "Alamat email belum diverifikasi! Buka email anda lalu klik verifikasi!"
            ], 401);
        }
    
        return $this->respondWithToken($token);
    }

    public function verifyEmail($id, $hash) {
        $user = User::find($id);
        if ($hash === base64_encode($user->name.'_'.$user->email)){
            $user->markEmailAsVerified();
            return response()->json(["message" => "Alamat email anda sudah diverifikasi!"]);
        }

        return response()->json(["meesage" => "Tautan verifikasi tidak valid"], 401);
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