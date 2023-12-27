<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use App\Models\User;
class UserController extends Controller
{
    public function index(){
        $users = User::latest()->get();

        return $this->success(["message" => "Retrieve all Data Success", "data" => $users]);
    }

    public function show($identifier){
       $user = User::where('id', $identifier)
        ->orWhere('username', $identifier)
        ->first()
       ;

       if($user){
            return $this->success(["message" => "User Found!" , "Data" => $user]);
       }

        return $this->failed(["message" => "User not Found!"]);
    }

    public function update(Request $request, $identifier){

        $validator = Validator::make($request->all(), [
            'username' => 'sometimes|string|min:3',
            'password' => 'sometimes|string|min:5'
        ]);
    
        if ($validator->fails()) {
            return $this->invalidField($validator->errors());
        }
        
        /*
        * Encrypt the new updated Password
        */ 
        if($request->password){
            $request->merge(['password' => bcrypt($request->input('password'))]);
        }

       $user = User::where('id', $identifier)
       ->orWhere('username', $identifier)
       ->update($request->toArray());//OPTIONAL atau yg dibawah

       if($user){
        //$user->update($request->toArray()); OPTIONAL
        return $this->success(["message" => "User Updated"]);
       }

       return $this->failed(["message" => "Error when updating data"]);
    }

    public function delete($identifier){
        $user = User::where('id', $identifier)
        ->orWhere('username', $identifier)
        ->delete();//OPTIONAL atau yg dibawah

        if ($user) {
            return $this->success(['message' => 'User Vanished!']);
        }

        //data post not found
        return $this->failed(['message' => 'User not Found']);
    }

    public function invalidField($err){
        return response()->json(["success" => false ,"message" => "Invalid Field", "error" => $err]);
    }

    public function failed($data){
        $data['success'] = false;
        return response()->json($data, 401);
    }

    public function success($data){
        $data['success'] = true;
        return response()->json($data);
    }
}
