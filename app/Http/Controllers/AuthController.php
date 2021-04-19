<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request){
        $rules = [
            'fullname' => 'required|min:1|max:200',
            'dob' => 'required|date|date_format:Y/m/d|before:17 years ago',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:6',
        ];

        $message = [
            'required' => 'The :attribute field is required.',
            'unique' => 'This email already exist.',
            'email' => 'Must be a valid email',
            'password.min' => 'The password must has 6 character minimun.',
            'dob.before' => 'You must be older than 18 years.',
            'dob.date' => 'Date format invalid',
        ];

        $validator = Validator::make($request->all(),$rules, $message);

        if($validator->fails()){
            return response()->json([
                'ok' => false,
                'msg' => 'Bad request',
                'errors' => $validator->errors()
            ],400);
        }

        $user = new User();
        $user->fullname = e($request->fullname);
        $user->dob = e($request->dob);
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'ok' => true,
            'msg' => 'User registered succesfully',
        ],200);

    }

    public function login( Request $request ){
        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];

        $validator = Validator::make($request->all(),$rules);

        if($validator->fails()){
            return response()->json([
                'ok' => false,
                'msg' => 'Bad request',
                'errors' => $validator->errors()
            ],400);
        }

        $credentials = $request->only('email', 'password');

        if(!Auth::attempt($credentials)){
            return response()->json([
                'msg' => 'Credential doesn\'t exist'
            ],401);
        }

        $user = User::where('email',$request->email)->first();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'ok' => true,
            'msg'=> 'Logged successfully',
            'user' => $user,
            'token' => $token,
        ],200);
    }

    public function logout( Request $request ){
        $request->user()->tokens()->delete();
        return response()->json([
            'ok' => true,
            'msg' => 'User logout successfully'
        ],200);
    }
}
