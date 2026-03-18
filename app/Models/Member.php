<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $fillable = [
        'persi_code',
        'name',
        'member_class_id',
        'dues_amount',
        'address',
        'email',
        'pic_whatsapp',
        'is_active'
    ];

    protected $casts = [
        'dues_amount' => 'integer',
        'is_active' => 'boolean',
    ];

    public function memberClass()
    {
        return $this->belongsTo(MemberClass::class);
    }
}
