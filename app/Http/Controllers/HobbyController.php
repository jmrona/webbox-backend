<?php

namespace App\Http\Controllers;

use App\Models\Hobby;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HobbyController extends Controller
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
            'msg' => 'User\'s hobbies got successfully',
            'hobbies' => Hobby::where('user_id', $user->id)->get(),
        ],200);
    }

    public function store(Request $request) {
        $rules = [
            'name' => 'required|min:1|max:200',
            'age' => 'required|numeric|min:1|max:100',
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

        $hobby = new Hobby();
        $hobby->name = $request->name;
        $hobby->age = $request->age;
        $hobby->user_id = Auth::user()->id;

        if(!$hobby->save()){
            return response()->json([
                'ok' => false,
                'msg' => 'Something was wrong, tried later again',
            ],400);
        };

        return response()->json([
            'ok' => true,
            'msg' => 'Hobby created successfully',
            'hobbies' => Hobby::where('user_id', Auth::user()->id)->get(),
        ],200);
    }

    public function update(Request $request, $id) {
        $rules = [
            'name' => 'required|min:1|max:200',
            'age' => 'required|numeric|min:1|max:100',
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

        $hobby = Hobby::find($id);
        $hobby->name = $request->name;
        $hobby->age = $request->age;

        if(!$hobby->save()){
            return response()->json([
                'ok' => false,
                'msg' => 'Something was wrong, tried later again',
            ],400);
        };

        return response()->json([
            'status' => 200,
            'ok' => true,
            'msg' => 'Hobby updated successfully',
            'hobbies' => Hobby::where('user_id', Auth::user()->id)->get(),
        ]);

    }

    public function destroy($id) {
        $hobby = Hobby::where('id',$id)->first();

        if(!$hobby){
            return response()->json([
                'ok' => false,
                'msg' => 'Hobby could not be deleted, try later again',
            ],400);
        }

        $hobby->delete();
        return response()->json([
            'ok' => true,
            'msg' => 'Hobby deleted successfully',
            'hobbies' => Hobby::where('user_id', Auth::user()->id)->get(),
        ],200);
    }
}
