<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Validator;

class NewsController extends Controller
{
    
    public function index(){
        $News = News::latest()->get();

        return $this->success(['message' => 'Data received successfully', "data" => $News]);
    }

    public function show($identifier){
       $News = News::where('id', $identifier)
        ->orWhere('news_title', $identifier)
        ->orWhere('news_content', $identifier)
        ->orWhere('news_image', $identifier)
        ->first();

       if($News){
            return $this->success(["message" => "Data Found!", "data" => $News]);
       }

        //make response JSON
        return $this->failed(["message" => "Data not Found!",]);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
           'news_title' => 'required',
           'news_content' => 'required',
           'news_image' => 'required|image|mimes:png,jpeg,jpg,webp,gif|max:2048',
        ]);


        if ($validator->fails()) {
            return $this->invalidField($validator->errors());
        }

        $image = $request->file('image');

        $imageName = time() . "." . $image->extension();

        $user = News::create(array_merge(
            $validator->validated(),
            ['news_image' => $imageName]
        )
        );
        
        if($user){
            return $this->success(['message' => "Storing Succeeded!"]);
        }

        $this->failed(['message' => "Storing Failed"]);
    }

    public function update(Request $request, $identifier){

        $validator = Validator::make($request->all(), [
            'news_title' => 'sometimes|string',
            'news_content' => 'sometimes|string',
            'news_image' => 'required|image|mimes:png,jpeg,jpg,webp,gif|max:2048'
        ]);
    
        if ($validator->fails()) {
            return $this->invalidField($validator->errors());
        }

        
       $News = News::where('id', $identifier)
       ->orWhere('news_title', $identifier)
       ->orWhere('news_content', $identifier)
       ->orWhere('news_image', $identifier)
       ->update($request->toArray());//OPTIONAL atau yg dibawah

       if($News){
        //$user->update($request->toArray()); OPTIONAL
        return $this->success(['message' => "Data Updated!"]);

       }

       return $this->failed(['message' => "Error when Updating Data, Data not Found"]);
        
    }

    public function destroy($identifier){
        $user = News::where('id', $identifier)
        ->orWhere('news_title', $identifier)
        ->orWhere('news_content', $identifier)
        ->orWhere('news_image', $identifier)
        ->delete();

        if ($user) {
            return $this->success(['message' => "Data Deleted!"]);
        }

        //data post not found
        return $this->failed(['message' => "Error when Deleting Data, Data not Found"]);
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
