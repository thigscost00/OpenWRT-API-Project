<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoutConf extends Model
{
    public $timestamps = false;

    protected $table = 'rout_confs';
    protected $fillable = [
        'router_id',
        'configuration_id'
    ];
}
