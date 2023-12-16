<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Validator;

class NewsController extends Controller
{
    
    public function index(){
        $Instances = News::latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'List Data',
            'data'    => $Instances
        ], 200);
    }

    public function show($identifier){
       $Instance = News::where('id', $identifier)
        ->orWhere('news_title', $identifier)
        ->orWhere('news_content', $identifier)
        ->orWhere('news_image', $identifier)
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
           'news_title' => 'required',
           'news_content' => 'required',
           'news_image' => 'required|image|mimes:png,jpeg,jpg,webp,gif|max:2048',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $image = $request->file('image');

        $imageName = time() . "." . $image->extension();

        $user = News::create(array_merge(
            $validator->validated(),
            ['news_image' => $imageName]
        )
        );
        
        if($user){
            return response()->json([
                'message' => "Storing Succeeded!"
            ], 201);
        }
    }

    public function update(Request $request, $identifier){

        $validator = Validator::make($request->all(), [
            'news_title' => 'sometimes|string',
            'news_content' => 'sometimes|string',
            'news_image' => 'required|image|mimes:png,jpeg,jpg,webp,gif|max:2048'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        
       $Instance = News::where('id', $identifier)
       ->orWhere('news_title', $identifier)
       ->orWhere('news_content', $identifier)
       ->orWhere('news_image', $identifier)
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
        $user = News::where('id', $identifier)
        ->orWhere('news_title', $identifier)
        ->orWhere('news_content', $identifier)
        ->orWhere('news_image', $identifier)
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
