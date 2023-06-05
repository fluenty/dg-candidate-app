<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public function scorings(){
        return $this->hasMany('App\Scoring', 'scoring_type_id', 'scoring_type_id')->orderBy('order', 'ASC');
    }
    public function scoringType(){
        return $this->hasOne('App\ScoringType', 'id', 'scoring_type_id');
    }
}
