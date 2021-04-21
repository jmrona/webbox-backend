<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class AvatarController extends Controller
{
    public function store(Request $request)
    {
        // return $request->file('file');
        $user = Auth::user();

        if(!$request->hasFile('file')){
            return response()->json([
                'ok' => false,
                'msg' => 'No file found',
            ],400);
        }

        if($request->hasFile('file')){

            if($user->avatar){
                $dir = '/'.$user->id.'/'.$user->avatar;
                $full_path = public_path('storage/img/avatar').$dir;
                unlink($full_path);

                $userAvatar = User::where('id', $user->id)->first();
                $userAvatar->avatar = null;
                $userAvatar->save();
            }

            $file = $request->file;
            $dir = '/'.$user->id;
            $full_path = public_path('storage/img/avatar').$dir;
            // localhost:8000/storage/img/avatar/$id/$image

            // Checking if the directory exist
            if (!file_exists($full_path)) {
                mkdir($full_path, 666, true);
            }

            $file_ext = trim($file->getClientOriginalExtension());
            if($file_ext !== 'png' && $file_ext !== 'jpg' && $file_ext !== 'jpeg'){
                return response()->json([
                    'ok' => false,
                    'msg' => 'The picture must be jpg or png format.'
                ],400);
            }

            $file_name = $user->id.'-'.Str::random(10).'.'.$file_ext;
            $final_file = $full_path.'/'.$file_name;
            $img = Image::make($file);
            $img->resize(200,200, function( $constraint) {
                $constraint->aspectRatio();
            });
            $img->save($final_file);

            $userUpdating = User::where('id', $user->id)->first();
            $userUpdating->avatar = $file_name;
            $userUpdating->save();

            return response()->json([
                'ok' => true,
                'msg' => 'Avatar updated successfully',
            ],200);
        }

        return response()->json([
            'ok' => false,
            'msg' => 'Avatar could no be uploaded',
        ],400);

    }

    public function destroy()
    {
        $user = Auth::user();
        if(!$user->avatar){
            return response()->json([
                'ok' => false,
                'msg' => 'There is not an avatar available',
            ], 400);
        }

        $dir = '/'.$user->id.'/'.$user->avatar;
        $full_path = public_path('storage/img/avatar').$dir;
        unlink($full_path);

        $userAvatar = User::where('id', $user->id)->first();
        $userAvatar->avatar = null;
        $userAvatar->save();

        return response()->json([
            'ok' => true,
            'msg' => 'Avatar deleted successfully',
        ],200);
    }
}
