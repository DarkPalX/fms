<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AllowEditLogs extends Model
{
    public $table = 'fms_fuel_request_allow_edit_logs';
    protected $fillable = [ 'request_id', 'allowed_by', 'user_name', 'edited_by'];

    
}
