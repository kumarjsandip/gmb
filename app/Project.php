<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function campaigns(){
        return $this->hasMany('App\Campaign','project_id');
    }
    
}
