<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use Validator;
use DateTime;

class PatientController extends Controller
{
    public function index()
    {
        $data = Patient::with('gender','service')->get();

        $response = array();
        foreach ($data as $value) {
            $data = array();
            $data['name'] = $value['name'];
            $data['dob'] = date("d/m/Y", strtotime($value['dob'])); ;
            $data['comments'] = $value['comments'];
            $data['gender'] = $value['gender']['name'];
            $data['service'] = $value['service']['name'];
            $data['id'] = $value['id'];

            array_push($response, $data);
        }

        return response($response);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required|unique:tbl_patient',
            'dob'=>'required',
            'type_of_service'=>'required|numeric',
            'gender'=>'required|numeric',
            'comments'=>'required'
        ],[
            'comments.required'=>'This field is required'
        ]);

        if ($validator->passes()) {
            $patient = new Patient;
            $patient->name = $request->name;
            $patient->dob = date('Y-m-d',strtotime($request->dob));
            $patient->tbl_services_id = $request->type_of_service;
            $patient->tbl_genders_id = $request->gender;
            $patient->comments = $request->comments;
            $patient->save();

            return response(['message' => 'Patient saved successfully!','status' => 'Success']);
        }
        
        return response()->json(['errors' => $validator->errors(),'status' => 'Fail']);
    }
}
