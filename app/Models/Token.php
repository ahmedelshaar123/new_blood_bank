<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model 
{

    protected $table = 'tokens';
    public $timestamps = true;
    protected $fillable = array('platform', 'token', 'client_id');
    protected $hidden = array('token');

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

}