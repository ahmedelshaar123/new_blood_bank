<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationRequest extends Model
{

    protected $table = 'donation_requests';
    public $timestamps = true;
    protected $fillable = array('name', 'age', 'bags_num', 'hos_name', 'hos_address', 'lat', 'lng', 'phone', 'notes', 'blood_type_id', 'city_id');

    public function notification()
    {
        return $this->hasOne('App\Models\Notification');
    }

    public function city()
    {
        return $this->belongsTo('App\Models\City');
    }
    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function bloodType()
    {
        return $this->belongsTo('App\Models\BloodType');
    }

}
