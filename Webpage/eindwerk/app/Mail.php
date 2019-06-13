<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mail extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['email'];
    
    protected $dates = ['deleted_at'];
}