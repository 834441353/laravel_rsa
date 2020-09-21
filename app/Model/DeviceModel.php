<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DeviceModel extends Model
{
    //
    protected $table = "yx_device";

//    protected $primaryKey = "d_mac";
    protected $primaryKey = "d_id";

    protected $fillable = ["d_did","d_mac", "d_chipid","d_starttime", "d_endtime", "d_companyid", "d_productname", "d_version", "d_name", "d_tel", "status","d_collectStatus","d_liveness","updated_at"];

    public function getCompanyname()
    {
        return $this->hasOne(\App\Model\CompanyModel::class, "c_id", "d_companyid");
    }
}
