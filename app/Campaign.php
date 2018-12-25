<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    public function project(){
        return $this->belongsTo('App\Project');
    }

    public function pctable(){
        return $this->hasOne('App\Projectcampaigntable','campaign_id');
    }

    public function phoneverify(){
        return $this->hasOne('App/Phonenumber','campaign_id');
    }

    
}
