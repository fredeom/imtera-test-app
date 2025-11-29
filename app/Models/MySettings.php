<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MySettings extends Model
{
    // const UPDATED_AT = null;
    // const CREATED_AT = null;
    public $timestamps = false;

    public function getTable()
    {
        return 'mysettings';
    }

    protected $fillable = [
        'user_id',
        'url',
    ];
}
