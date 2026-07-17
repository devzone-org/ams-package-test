<?php


namespace Devzone\Ams\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminExpense extends Model
{
    use SoftDeletes;

    protected $guarded = [];
}
