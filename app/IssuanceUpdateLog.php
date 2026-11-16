<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;

class IssuanceUpdateLog extends Model
{
    public $table = 'issuance_request_update_logs';

    protected $fillable = ['request_id', 'attachment', 'user_id', 'update_logs'];

}
