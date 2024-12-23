<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function upload(Request $request)
    {
        // Validasi file yang diupload
        $this->validate($request, [
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048', // Max 2MB
        ]);

        // Ambil file dari request
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            
            // Generate nama unik untuk file
            $fileName = time() . '_' . $file->getClientOriginalName();

            // Simpan file di folder storage/app/uploads
            $file->move(storage_path('app/uploads'), $fileName);

            return response()->json(['message' => 'File berhasil diupload', 'filename' => $fileName], 200);
        }

        return response()->json(['message' => 'Tidak ada file yang diupload'], 400);
    }
}
