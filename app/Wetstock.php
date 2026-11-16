<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Auth;
use DB;

class Wetstock extends Model
{
    
    public $table = 'wetstocks';

    protected $guarded = [];

    public function tank() {
        return $this->belongsTo(Tank::class);
    }

	
}
