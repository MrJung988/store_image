<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    public function storeImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation errors',
                'error' => $validator->errors(),
                'success' => false,
            ], 400);
        }

        $image = new Image();
        if ($request->hasFile('image')) {
            $imageFile = $request->file('image');
            $extension = $imageFile->getClientOriginalExtension();
            $imageName = 'images/' . time() . '.' . $request->image->extension();

            $imageFile->move(public_path('images'), $imageName);
            $image->path = $imageName;
        }
        $image->save();

        return response()->json([
            'message' => 'Image uploaded successfully',
            'success' => true,
        ], 200);
    }
}
