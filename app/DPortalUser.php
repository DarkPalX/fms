<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DPortalUser extends Model
{

    public $connection = 'sqlsrv_dportal';

    public $guarded = [];

    public $table = 'users';


}
