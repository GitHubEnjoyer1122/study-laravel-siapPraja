<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Student;
class AuthController extends Controller
{
    public function store(){
    ini_set('max_execution_time', 300);

        $Student = Student::where("tahun_angkatan", "=", strval(now()->year-1).'/'.strval(now()->year))->get();
        $i = 0;

        $chunkSize = 100;
        $chunks = $Student->chunk($chunkSize);

        foreach ($chunks as $chunkIndex) {
            foreach($chunkIndex as $datas){
            $i++;
            $user = User::create(
                [
                    "name" => $datas->nama_siswa,
                    "username" => $datas->nisn,
                    "password" => $datas->nisn
                ]
            );
            }
        }
        
        if(!$i){
            return $this->success(['message' => "Data Updated!", "Total Data Inputted" => strval($i)." times"]);
        }
    }
    
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'username' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->invalidField($validator->errors());
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
        return $this->success(['message' => "User Logging Out Successfully!"]);
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
