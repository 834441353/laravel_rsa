<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DeviceModel extends Model
{
    //
    protected $table = "yx_device";

    protected $primaryKey = "d_mac";

    protected $fillable = ["d_starttime","d_endtime","d_company","d_productname","d_version","d_name","d_tel","status"];
}
