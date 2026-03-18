<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UmkRealization extends Model
{
    protected $fillable = [
        'umk_request_id',
        'trx_date',
        'type',
        'amount',
        'description',
        'cash_transaction_id',
        'created_by',
    ];

    protected $casts = [
        'trx_date' => 'date',
        'amount' => 'integer',
    ];

    public function umk()
    {
        return $this->belongsTo(UmkRequest::class, 'umk_request_id');
    }

    public function cashTransaction()
    {
        return $this->belongsTo(CashTransaction::class);
    }
}
