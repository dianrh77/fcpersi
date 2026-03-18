<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoaAccount extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'level',
        'parent_id',
        'is_active',
    ];

    protected $casts = [
        'level' => 'integer',
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
