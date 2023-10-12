<?php

namespace Devzone\Ams\Models;

use Illuminate\Database\Eloquent\Model;

class LedgerSettlement extends Model
{
    protected $table = "ledger_settlements";
    public $timestamps = false;

    protected $guarded = [];
}
