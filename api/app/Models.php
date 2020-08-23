<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed name
 */
class Models extends Model
{
    function Labels(){
        return $this->hasMany('App\Labels', 'model_id', 'id');
    }
}
