<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Auth;
use DB;

class Dispatch extends Model
{
	protected $connection = 'sqlsrv_vms';
    public $table = 'dispatch';
   	
	
}
