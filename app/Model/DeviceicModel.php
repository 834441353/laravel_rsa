<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class DeviceicModel extends Model
{
    //
    protected $table = "yx_deviceIc";

    protected $primaryKey = "deviceIc_id";

    protected $fillable = ["deviceIc_mac","deviceIc_version","deviceIc_collectStatus","deviceIc_liveness"];
}
