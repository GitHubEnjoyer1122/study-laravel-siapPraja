<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instance;
use Validator;

class InstanceController extends Controller
{
    
    public function index(){
        $Instances = Instance::latest()->get();

        return $this->success(['message' => "Data Found!", "data lists" => $Instances]);

    }

    public function show($identifier){
       $Instance = Instance::where('id', $identifier)
        ->orWhere('instance_name', $identifier)
        ->orWhere('instance_phone_number', $identifier)
        ->orWhere('instance_address', $identifier)
        ->first();

       if($Instance){
        return $this->success(['message' => "Data Found!", "data" => $Instance]);
       }

        //make response JSON
        return $this->failed(['message' => "Data Not Found!"]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
           'instance_name' => 'required',
           'instance_phone_number' => 'required',
           'instance_address' => 'required',
        ]);


        if ($validator->fails()) {
            return $this->invalidField($validator->errors());
        }


        $user = Instance::create(
            $validator->validated()
        );
        
        if($user){
            return $this->success(['message' => "Data Stored!"]);
        }

        return $this->failed(['message' => "Data Storing Failed!"]);
    }

    public function update(Request $request, $identifier){

        $validator = Validator::make($request->all(), [
            'instance_name' => 'sometimes|string|min:3',
            'instance_phone_number' => 'sometimes|string|min:5',
            'instance_address' => 'sometimes|string|min:5'
        ]);
    
        if ($validator->fails()) {
            return $this->invalidField($validator->errors());
        }

        
       $Instance = Instance::where('id', $identifier)
       ->orWhere('instance_name', $identifier)
       ->orWhere('instance_phone_number', $identifier)
       ->orWhere('instance_address', $identifier)
       ->update($request->toArray());//OPTIONAL atau yg dibawah

       if($Instance){
        //$user->update($request->toArray()); OPTIONAL
            return $this->success(['message' => "Data Updated!"]);
       }

       return $this->failed(['message' => "Data Not Found!"]);
    }

    public function destroy($identifier){
        $user = Instance::where('id', $identifier)
        ->orWhere('instance_name', $identifier)
        ->orWhere('instance_phone_number', $identifier)
        ->orWhere('instance_address', $identifier)
        ->delete();

        if ($user) {

            return $this->success(['message' => "Data Deleted Successfully!"]);
        }

        //data post not found
        return $this->failed(['message' => "Data Not Found!"]);
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
