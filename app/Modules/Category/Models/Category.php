<?php

namespace App\Modules\Category\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'status', 'comment', 'create_user_id', 'create_at', 'update_user_id', 'update_at'];
}
