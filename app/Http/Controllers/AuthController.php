<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\Rule;
use App\Models\User;
class AuthController extends Controller
{
    public function storeUser(Request $request){
        $validator = Validator::make($request->all(),[
           'username' => 'required',
           'password' => 'required',
           'name' => 'required',
           'levels' => ['required',Rule::in([1, 2, 3, 4])],
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }


        $user = User::create(
            $validator->validated()
        );
        
        if($user){
            return response()->json([
                'message' => "User stored Successfully!"
            ], 201);
        }
    }
    
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'username' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(),422);
        }

        if(!$token=auth()->attempt($validator->validated())){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();
        $userLevel = $user->levels;
    
        // Include the user's level in the token payload
        $token = JWTAuth::fromUser($user, ['levels' => $userLevel]);

        return $this->createNewToken($token);
    }

    public function createNewToken($token){
        return response()->json(
        [
            'access_token' => $token
        ]);
    }

    public function logout(){
        auth()->logout();
        return response()->json([
            "message" => "User logout Successfully!"
        ], 201);
    }
}
