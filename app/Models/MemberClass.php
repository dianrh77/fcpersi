<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberClass extends Model
{
    protected $fillable = [
        'code',
        'name',
        'default_dues_amount',
    ];

    protected $casts = [
        'default_dues_amount' => 'integer',
    ];

    public function members()
    {
        return $this->hasMany(Member::class);
    }
}
