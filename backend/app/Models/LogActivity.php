<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    protected $table = 'log_activity';
    public $timestamps = false;
    protected $fillable = ['user_id', 'table_name', 'action'];
}
