<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CompanyModel extends Model
{
    //
    protected $table = "yx_company";
    protected $primaryKey = "c_id";
    protected $fillable = ["c_companyname","updated_at","created_at","comment"];
    public function getDevices(){
        return $this->hasMany(\App\Model\DeviceModel::class,"d_companyid","c_id");
    }

}
