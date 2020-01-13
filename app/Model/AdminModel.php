<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class AdminModel extends Model
{
    protected $table = "yx_admin";
    protected $primaryKey = "a_id";
    protected $fillable = ["a_username","a_password","comment"];

}
