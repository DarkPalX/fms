<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Users extends Authenticatable
{
    protected $fillable = [
        'domainAccount', 'password','name','role','dept','isActive','created_at','updated_at','remember_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public $table='fms_users_tbl';


    public static function employee_lookup()
    {
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=> false,
                "verify_peer_name"=> false,
            ),
        );

        $employees = file_get_contents("https://mlappsvr.philsaga.com/fms/api/hris-api.php", false, stream_context_create($arrContextOptions));

        return $employees;
    }

    public static function user_details($domain)
    {
        $data = Users::where('domainAccount',$domain)->first();

        return $data->name;
    }
}