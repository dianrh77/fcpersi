<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashTransaction extends Model
{
    protected $fillable = [
        'trx_date',
        'type',
        'cash_account_id',
        'member_id',
        'coa_account_id',
        'amount',
        'reference_no',
        'description',
        'created_by'
    ];

    public function cashAccount()
    {
        return $this->belongsTo(CashAccount::class);
    }
    public function coaAccount()
    {
        return $this->belongsTo(CoaAccount::class);
    }
    public function member()
    {
        return $this->belongsTo(Member::class);
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
