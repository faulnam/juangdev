<?php

namespace App\Http\Controllers;

use App\Helpers\ImageCompressor;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp,svg|max:10240',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $url = ImageCompressor::uploadAndCompress($file);

            return response()->json([
                'success' => true,
                'url' => $url,
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No image uploaded'], 400);
    }
}
