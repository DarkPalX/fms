<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Downtime extends Model
{
    public $table = 'fms_downtime';

    protected $fillable = [ 'startdate', 'enddate', 'reason', 'encoded_by', 'status'];
    
    public $timestamps = false;
}
