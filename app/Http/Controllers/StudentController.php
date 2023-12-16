<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function getAllStudent(){
        $Student = Student::all();
        
        if($Student){
            return response()->json([
                "Message" => "Data received!",
                "Data :" => $Student
            ]);
        }
    }
}
