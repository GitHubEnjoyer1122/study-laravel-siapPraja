<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Instance;
use Validator;

class InstanceController extends Controller
{
    
    public function index(){
        $Instances = Instance::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data',
            'data'    => $Instances
        ], 200);
    }

    public function show($identifier){
       $Instance = Instance::where('id', $identifier)
        ->orWhere('instance_name', $identifier)
        ->orWhere('instance_phone_number', $identifier)
        ->orWhere('instance_address', $identifier)
        ->first();

       if($Instance){
            return response()->json([
            "info" => "Success",
            "message" => "Data Found!",
            "Data" => $Instance
            ], 200);
       }

        //make response JSON
        return response()->json([
            "info" => "Error",
            "message" => "Data not Found!",
            'Supposedly Null' => $Instance
        ], 404);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
           'instance_name' => 'required',
           'instance_phone_number' => 'required',
           'instance_address' => 'required',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }


        $user = Instance::create(
            $validator->validated()
        );
        
        if($user){
            return response()->json([
                'message' => "Storing Succeeded!"
            ], 201);
        }
    }

    public function update(Request $request, $identifier){

        $validator = Validator::make($request->all(), [
            'instance_name' => 'sometimes|string|min:3',
            'instance_phone_number' => 'sometimes|string|min:5',
            'instance_address' => 'sometimes|string|min:5'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        
       $Instance = Instance::where('id', $identifier)
       ->orWhere('instance_name', $identifier)
       ->orWhere('instance_phone_number', $identifier)
       ->orWhere('instance_address', $identifier)
       ->update($request->toArray());//OPTIONAL atau yg dibawah

       if($Instance){
        //$user->update($request->toArray()); OPTIONAL
        return response()->json([
            "info" => "success",
            "Message" => "Data Updated!",
        ]);
       }

       return response()->json([
        "info" => "Error",
        "Message" => "Error when updating data"
    ]);
        
    }

    public function destroy($identifier){
        $user = Instance::where('id', $identifier)
        ->orWhere('instance_name', $identifier)
        ->orWhere('instance_phone_number', $identifier)
        ->orWhere('instance_address', $identifier)
        ->delete();

        if ($user) {

            return response()->json([
                'info' => "Success",
                'message' => 'Data Vanished!',
            ], 200);
        }

        //data post not found
        return response()->json([
            'info' => "error",
            'message' => 'Data Not Found',
        ], 404);
    }
}
