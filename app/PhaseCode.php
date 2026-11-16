<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PhaseCode extends Model
{
    public $table = 'fms_phase_code';

    protected $fillable = [ 'phase_code', 'phase_desc' ];
    
    public $timestamps = false;
}
