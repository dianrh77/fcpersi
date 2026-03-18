<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanRealization extends Model
{
    protected $fillable = [
        'kegiatan_request_id',
        'trx_date',
        'type',
        'coa_account_id',
        'amount',
        'description',
        'cash_transaction_id',
        'created_by',
    ];

    protected $casts = [
        'trx_date' => 'date',
        'amount' => 'integer',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(KegiatanRequest::class, 'kegiatan_request_id');
    }

    public function cashTransaction()
    {
        return $this->belongsTo(CashTransaction::class);
    }

    public function coaAccount()
    {
        return $this->belongsTo(CoaAccount::class);
    }
}
