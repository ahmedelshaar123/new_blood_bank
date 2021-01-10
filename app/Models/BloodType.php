<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodType extends Model 
{

    protected $table = 'blood_types';
    public $timestamps = true;
    protected $fillable = array('type');

    public function clients()
    {
        return $this->hasMany('App\Models\Client');
    }

    public function clientsBTM()
    {
        return $this->belongsToMany('App\Models\Client');
    }

    public function donationRequests()
    {
        return $this->hasMany('App\Models\DonationRequest');
    }

}