<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gender;
use Validator;
class GenderController extends Controller
{

    public function index()
    {
        return Gender::all();
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required|unique:tbl_gender'
        ]);

        if ($validator->passes()) {
            $gender = new Gender;
            $gender->name = $request->name;
            $gender->save();

            return response(['message' => 'Gender saved successfully!','status' => 'Success']);
        }
        
        return response()->json(['errors' => $validator->errors(),'status' => 'Fail']);
    }

   
}
