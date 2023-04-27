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

    protected static function boot()
    {
        parent::boot();

        $posting_date = ClosingSummaryAccounts::orderBy('posting_date', 'asc')->select('posting_date')->first()->posting_date ?? null;

        if (!empty($posting_date))
        {
            static::creating(function ($model) use ($posting_date) {
                if ($model->posting_date <= $posting_date) {
                    throw new \Exception('The fiscal year of this posting date has been closed. Posting date must be greater than ' . date('d M, Y', strtotime($posting_date)));
                }
            });

            static::updating(function ($model) use ($posting_date) {
                if ($model->posting_date <= $posting_date) {
                    throw new \Exception('The fiscal year of this posting date has been closed. Posting date must be greater than ' . date('d M, Y', strtotime($posting_date)));
                }
            });
        }
    }

}
