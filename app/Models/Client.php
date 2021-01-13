<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{

    protected $table = 'clients';
    public $timestamps = true;
    protected $fillable = array('name', 'email', 'date_of_birth', 'last_date_of_donation', 'phone', 'password', 'is_active', 'pin_code', 'api_token', 'city_id', 'blood_type_id');
    protected $hidden = array('password', 'pin_code', 'api_token');

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }

    public function bloodType()
    {
        return $this->belongsTo('App\Models\BloodType');
    }

    public function articles()
    {
        return $this->belongsToMany('App\Models\Article');
    }

    public function tokens()
    {
        return $this->hasMany('App\Models\Token');
    }

    public function bloodTypes()
    {
        return $this->belongsToMany('App\Models\BloodType');
    }

    public function governorates()
    {
        return $this->belongsToMany('App\Models\Governorate');
    }

    public function notifications()
    {
        return $this->belongsToMany('App\Models\Notification');
    }

}
