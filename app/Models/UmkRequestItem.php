<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UmkRequestItem extends Model
{
    protected $fillable = [
        'umk_request_id',
        'coa_account_id',
        'item_name',
        'amount',
        'description',
    ];

    /* ================= RELATIONS ================= */

    public function umkRequest()
    {
        return $this->belongsTo(UmkRequest::class);
    }

    public function coaAccount()
    {
        return $this->belongsTo(CoaAccount::class);
    }
}
