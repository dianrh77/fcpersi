<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KegiatanRequest extends Model
{
    protected $fillable = [
        'activity_no',
        'activity_date',
        'activity_name',
        'activity_description',
        'cash_account_id',
        'budget_amount',
        'status',
        'created_by',
    ];

    protected $casts = [
        'activity_date' => 'date',
        'budget_amount' => 'integer',
    ];

    public function cashAccount()
    {
        return $this->belongsTo(CashAccount::class);
    }

    public function realizations()
    {
        return $this->hasMany(KegiatanRealization::class);
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
        $used = $this->expense_total - $this->income_total;
        return max(0, (int) $this->budget_amount - (int) $used);
    }
}
