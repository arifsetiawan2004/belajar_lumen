<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserCollection;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    // public function __construct()
    // {
    //     // $this->middleware('auth:api', ['except' => ['index', 'show', 'store', 'restore']]);
    // }
    public function index()
    {
        return new UserCollection(User::paginate());
        // $users = User::all();

        // $userList = [];
        // foreach ($users as $user){
        //     $userList[] = 
        //         [
        //         "email" => $user->email,
        //         "name" => $user->name
        //         ];
        // }
        // return response()->json($userList);
    }
    public function show($id)
    {
        $user = User::find($id);
        if ($user)
            return response()->json(["email" => $user->email,"name" => $user->name,"photo" => url('public/photo').$user->photo]);
        else
            return response()->json(["Error" => "User Tidak Ada"],404);
    }
    public function store(Request $request)
    {
        //Tambahkan validasi
        $this->validate($request,[
            'email' => 'required|email|unique:user',
            'name' => 'required|string|max:255',
            // 'password' => 'required',
        ]);
        $user = new User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->save();
        return response()->json(["message" => "User Berhasil Dibuat"]);
    }
    public function update(Request $request, $id)
    {
        // Cari user berdasarkan ID
        $user = User::find($id);
    
        // Jika user tidak ditemukan, kembalikan respon 404
        if (!$user) {
            return response()->json(["Error" => "User Tidak Ada"], 404);
        }
    
        // Validasi input menggunakan $this->validate
        $this->validate($request, [
            'email' => 'email|unique:user,email,' . $id,
            'name' => 'string|max:255',
        ]);
    
        // Update email jika ada di request
        if ($request->has('email')) {
            $user->email = $request->email;
        }
    
        // Update name jika ada di request
        if ($request->has('name')) {
            $user->name = $request->name;
        }
    
        // Simpan perubahan
        $user->save();
    
        return response()->json(["message" => "User Berhasil Diperbarui"]);
    }
        
    public function delete($id)
    {
        $user = User::find($id);
        if (!$user)
            return response()->json(["Error" => "User Tidak Ada"],404);
        else {
            $user->delete();
            return response()->json(["message" => "User Berhasil Dihapus"]);
       
        }

    }

    public function upload(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(["message" => "User tidak ada"], 404);
        }

        // Validasi file wajib diupload dan harus berupa gambar
        $this->validate($request, [
            'image' => 'required|file|mimes:jpg,jpeg,png|max:2048', // Maksimal 2MB
        ]);

        // Cek apakah file ada dan valid
        if (!$request->hasFile('image') || !$request->file('image')->isValid()) {
            return response()->json(['message' => 'Gambar tidak valid atau tidak ditemukan'], 400);
        }

        // Generate nama file unik
        $filename = Str::uuid() . '.jpg'; 
        $request->file('image')->move(base_path('public/photo'), $filename);        

        // Simpan nama file di kolom 'photo' user
        $user->photo = $filename;
        $user->save();

        return response()->json(['message' => 'Gambar berhasil diunggah']);
    }
    // Fungsi untuk merestore user yang dihapus
    public function restore($id)
    {
        // Cari user yang sudah dihapus (termasuk yang soft-deleted)
        $user = User::withTrashed()->find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        if ($user->trashed()) {
            $user->restore();
            return response()->json(['message' => 'User berhasil direstore']);
        }

        return response()->json(['message' => 'User tidak dalam kondisi terhapus'], 400);
    }


}