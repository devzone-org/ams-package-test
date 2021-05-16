<?php


namespace Devzone\Ams\Models;


use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    protected $guarded = [];

    public function attachments()
    {
        return $this->hasMany(LedgerAttachment::class,'voucher_no','voucher_no');
    }
}
