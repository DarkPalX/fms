<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    public $table = 'fms_activity_logs';

    public function details()
    {
    	return $this->belongsTo('\App\IssuanceRequest','transid');
    }
   
}
