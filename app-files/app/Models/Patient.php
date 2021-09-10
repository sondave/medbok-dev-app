<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'tbl_patient';

    protected $fillable = ['name','dob','tbl_services_id','tbl_genders_id','comments'];

    public function gender()
    {
        return $this->hasOne(Gender::class,'id','tbl_genders_id');
    }

    public function service()
    {
        return $this->hasOne(Service::class,'id','tbl_services_id');
    }
}
