<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UmkRequest extends Model
{
    protected $fillable = [
        'request_no',
        'request_date',
        'activity_name',
        'activity_description',
        'cash_account_id',
        'amount',
        'status',
        'created_by',
    ];

    protected $casts = [
        'request_date' => 'date',
        'amount' => 'integer',
    ];

    public function cashAccount()
    {
        return $this->belongsTo(CashAccount::class);
    }

    public function realizations()
    {
        return $this->hasMany(UmkRealization::class);
    }

    public function getExpenseTotalAttribute(): int
    {
        return (int) $this->realizations()->where('type', 'expense')->sum('amount');
    }

    public function getIncomeTotalAttribute(): int
    {
        return (int) $this->realizations()->where('type', 'income')->sum('amount');
    }

    public function getOutstandingAttribute(): int
    {
        // outstanding = UMK - (expense - income)
        $used = $this->expense_total - $this->income_total;
        return max(0, (int) $this->amount - (int) $used);
    }
}
