<?php


namespace Devzone\Ams\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccVendor extends Model
{
    use SoftDeletes;

    protected $guarded = [];
}
