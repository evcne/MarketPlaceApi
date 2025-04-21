<?php

namespace App\Modules\Company\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name',
        'address',
        'comment',
        'phone_number',
        'email',
        'tax_no',
        'tckn_no',
        'status',
        'create_user_id',
        'is_approved', //admin onay durumunu tutuyor
        'status', //delete durumunu tutuyor
    ];
}
