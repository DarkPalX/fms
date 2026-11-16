<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DPortalUserAccess extends Model
{

    public $connection = 'sqlsrv_dportal';

    public $guarded = [];

    public $table = 'user_access';

    public $timestamps = false;

}
