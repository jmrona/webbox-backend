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
            'user' => User::find($user->id)->with(['getHobbies'])->get(),
        ],200);
    }

    public function update(Request $request) {
        $rules = [
            'fullname' => 'required|min:1|max:200',
        ];

        $message = [
            'required' => 'The :attribute field is required.',
        ];

        $validator = Validator::make($request->all(),$rules, $message);

        if($validator->fails()){
            return response()->json([
                'ok' => false,
                'msg' => 'Bad request',
                'errors' => $validator->errors()
            ],400);
        }

        $user_id = Auth::user()->id;
        $user = User::where('id', $user_id)->first();
        $user->fullname = $request->fullname;
        $user->biography = $request->biography;

        if(!$user->save()){
            return response()->json([
                'ok' => false,
                'msg' => 'Something was wrong, tried later again',
            ],400);
        }

        return response()->json([
            'ok' => true,
            'msg' => 'User updated successfully',
            'user' => $user->with(['getHobbies'])->get(),
        ],200);

    }

    public function destroy(Request $request, $id) {
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
