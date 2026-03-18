<?php

namespace App\Models;

use App\Models\CashTransaction;
use Illuminate\Database\Eloquent\Model;

class CashAccount extends Model
{
    public function transactions()
    {
        return $this->hasMany(CashTransaction::class);
    }
}
