<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    public $timestamps = false;

    protected $table = 'configurations';
    protected $fillable = [
        'name',
    ];
}
