<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120', // Maks 5MB
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('media', $filename, 'public');

            return response()->json([
                'url' => Storage::disk('public')->url($path),
                'name' => $file->getClientOriginalName()
            ]);
        }

        return response()->json(['error' => 'Dosya yüklenemedi.'], 400);
    }
}
