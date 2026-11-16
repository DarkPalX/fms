<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FuelHistory extends Model
{
    public $table = 'fuel_history_tbl';

    protected $fillable = [ 'fuel_id', 'cost', 'updated_at', 'updated_by'];
    
    public $timestamps = false;
}
