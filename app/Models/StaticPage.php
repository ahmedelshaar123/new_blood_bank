<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model 
{

    protected $table = 'static_pages';
    public $timestamps = true;
    protected $fillable = array('name', 'value', 'type', 'key');
    protected $hidden = array('key');

}