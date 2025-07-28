<?php

namespace Devzone\Ams\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PnlTemplateManager extends Model
{
    use SoftDeletes;

    protected $guarded = [];
    protected $casts = [
        'income_accounts' => 'array',
        'expense_accounts' => 'array',
    ];
}