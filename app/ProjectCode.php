<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProjectCode extends Model
{
    public $table = 'fms_project_code';

    protected $fillable = [ 'project_code', 'project_desc', 'phase_code' ];
    
    public $timestamps = false;
}
