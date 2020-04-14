<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Updaterecord extends Model
{
    //
    protected $table = "yx_updaterecord";
    protected $primaryKey = "u_id";
    protected $fillable = ["u_id","u_mac","u_chipid","u_oldversion","u_newversion","status"];
}
