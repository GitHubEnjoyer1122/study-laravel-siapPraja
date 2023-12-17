<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    public function getAllStudent(){
        $Student = Student::where("tahun_angkatan", "=", strval(now()->year).'/'.strval(now()->year+1))->get();
        

        if($Student){
            return response()->json([
                "Message" => "Data received!",
                "Data :" => $Student
            ]);
        }
    }
}
