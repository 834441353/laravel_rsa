<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class StdeviceModel extends Model
{
    //
    protected $table = "yx_stdevice";

    protected $primaryKey = "st_id";

    protected $fillable = ["st_mac","st_chipid","st_version","st_collectStatus","st_liveness","reverse"];
}
