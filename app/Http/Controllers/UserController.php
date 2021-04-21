<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index() {
        $user = Auth::user();
        if(!$user){
            return response()->json([
                'ok' => false,
                'msg' => 'User no exist',
            ],400);
        }

        return response()->json([
            'ok' => true,
            'msg' => 'User got successfully',
            'user' => User::where('id',$user->id)->with(['getHobbies'])->first(),
        ],200);
    }

    public function update(Request $request) {
        $user_id = Auth::user()->id;
        if(!$user_id){
            return response()->json([
                'ok' => false,
                'msg' => 'Not allowed',
            ],400);
        }

        $user = User::where('id', $user_id)->first();
        if($request->fullname){
            $validator = Validator::make($request->all(),[
                'fullname' => 'required|min:1|max:200',
            ]);
            $user->fullname = $request->fullname;
        }

        if($request->biography){
            $validator = Validator::make($request->all(),[
                'biography' => 'min:1|max:2000',
            ]);
            $user->biography = $request->biography;
        }

        if($validator->fails()){
            return response()->json([
                'ok' => false,
                'msg' => 'Bad request',
                'errors' => $validator->errors()
            ],400);
        }

        if(!$user->save()){
            return response()->json([
                'ok' => false,
                'msg' => 'Something was wrong, tried later again',
            ],400);
        }

        return response()->json([
            'ok' => true,
            'msg' => 'User updated successfully',
            'user' => $user->with(['getHobbies'])->where('id',$user_id)->first(),
        ],200);

    }

    public function destroy(Request $request) {
        $user_id = Auth::user()->id;
        $user = User::where('id',$user_id)->first();
        if(!$user){
            return response()->json([
                'ok' => false,
                'msg' => 'User could not be deleted, try later again',
            ],400);
        }
        $request->user()->tokens()->delete();
        $user->delete();
        return response()->json([
            'ok' => true,
            'msg' => 'User deleted successfully',
        ],200);
    }
}
