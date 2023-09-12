<?php


namespace Devzone\Ams\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClosingSummaryAccounts extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
}
