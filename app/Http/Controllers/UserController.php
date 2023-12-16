<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\User;
class UserController extends Controller
{
    public function getAllUser(){
        $users = User::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data Post',
            'data'    => $users
        ], 200);
    }

    public function showOneUser($identifier){
       $user = User::where('id', $identifier)
        ->orWhere('username', $identifier)
        ->first()
       ;

       if($user){
            return response()->json([
            "info" => "Success",
            "message" => "User Found!",
            "Data" => $user
            ], 200);
       }

        //make response JSON
        return response()->json([
            "info" => "Error",
            "message" => "User not Found!",
            'Supposedly Null' => $user
        ], 404);
    }

    public function updateUser(Request $request, $identifier){

        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|string|min:3',
            'password' => 'sometimes|string|min:5'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        if($request->password){
            $request->merge(['password' => bcrypt($request->input('password'))]);
        }

       $user = User::where('id', $identifier)
       ->orWhere('username', $identifier)
       ->update($request->toArray());//OPTIONAL atau yg dibawah

       if($user){
        //$user->update($request->toArray()); OPTIONAL
        return response()->json([
            "info" => "success",
            "Message" => "User Updated!"
        ]);
       }

       return response()->json([
        "info" => "Error",
        "Message" => "Error when updating data"
    ]);
        
    }

    public function deleteUser($identifier){
        $user = User::where('id', $identifier)
        ->orWhere('username', $identifier)
        ->delete();//OPTIONAL atau yg dibawah

        if ($user) {

            return response()->json([
                'info' => "Success",
                'message' => 'User Vanished!',
            ], 200);
        }

        //data post not found
        return response()->json([
            'info' => "error",
            'message' => 'User Not Found',
        ], 404);
    }
}
