<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class PlatformModel extends Model
{
    //
    protected $table = "yx_platform";

//    protected $primaryKey = "d_mac";
    protected $primaryKey = "platform_id";

    protected $fillable = ["platform_platform"];
}
