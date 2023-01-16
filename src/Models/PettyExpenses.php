<?php


namespace Devzone\Ams\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PettyExpenses extends Model
{
    use SoftDeletes;

    protected $guarded = [];
}
