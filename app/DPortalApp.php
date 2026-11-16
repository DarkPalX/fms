<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DPortalApp extends Model
{

    public $connection = 'sqlsrv_dportal';

    public $guarded = [];

    public $table = 'apps';


}
