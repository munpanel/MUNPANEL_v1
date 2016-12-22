<?php

namespace App;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class Delegate extends Model
{
    protected $table='delegate_info';
    protected $primaryKey = 'user_id';
    protected $fillable = ['user_id','school_id','status','gender','sfz','grade','email','qq','wechat','partnername','parenttel','tel','committee_id','accomodate','roommatename'];

    public function committee() {
        return $this->belongsTo('App\Committee');
    }

    public function nation() {
        return $this->belongsTo('App\Nation');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function school() {
        return $this->belongsTo('App\School');
    }

    public function individual_assignments() {
        return $this->hasMany('App\Assignment');
    }

    public function nationgroups() {
        return $this->nation->nationgroups();
    }

    public function assignments() {
        $result = new Collection;
        if (is_null($this->nation))
            return $result;
        $nationgroups = $this->nationgroups;
        foreach($nationgroups as $nationgroup)
        {
            $assignments = $nationgroup->assignments;
            foreach ($assignments as $assignment)
                $result->push($assignment);
        }
        return $result->unique()->sortBy('id');
    }
}
