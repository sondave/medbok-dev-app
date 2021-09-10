<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use Validator;

class ServiceController extends Controller
{

    public function index()
    {
        return Service::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required|unique:tbl_service'
        ]);

        if ($validator->passes()) {
            $service = new Service;
            $service->name = $request->name;
            $service->save();

            return response(['message' => 'Service saved successfully!','status' => 'Success']);
        }
        
        return response()->json(['errors' => $validator->errors(),'status' => 'Fail']);
    }

    
}
