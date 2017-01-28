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

    public function delegategroups() {
        return $this->belongstoMany('App\Delegategroup');
    }

    public function partner() {
        return $this->belongsTo('App\User', 'partner_user_id');
    }

    public function assignments() {
        $result = new Collection;
        if (isset($this->nation))
        {
            $nationgroups = $this->nationgroups;
            if (isset($nationgroups))
            {
                foreach($nationgroups as $nationgroup)
                {
                    $assignments = $nationgroup->assignments;
                    if (isset($assignments))
                        foreach ($assignments as $assignment)
                            $result->push($assignment);
                }
            }
        }
        $assignments = $this->committee->assignments;
        if (isset($assignments))
        {
            foreach ($assignments as $assignment)
                $result->push($assignment);
        }
        $delegategroups = $this->delegategroups;
        if (isset($delegategroups))
        {
            foreach($delegategroups as $delegategroup)
            {
                $assignments = $delegategroup->assignments;
                if (isset($assignments))
                    foreach ($assignments as $assignment)
                        $result->push($assignment);
            }
        }
        return $result->unique()->sortBy('id');
    }
    
    public function documents() {
        $result = new Collection;
        if (isset($this->nation))
        {
            $nationgroups = $this->nationgroups;
            if (isset($nationgroups))
            {
                foreach($nationgroups as $nationgroup)
                {
                    $documents = $nationgroup->documents;
                    if (isset($documents))
                        foreach ($documents as $document)
                            $result->push($document);
                }
            }
        }
        $documents = $this->committee->documents;
        if (isset($documents))
        {
            foreach ($documents as $document)
                $result->push($document);
        }
        $delegategroups = $this->delegategroups;
        if (isset($delegategroups))
        {
            foreach($delegategroups as $delegategroup)
            {
                $documents = $delegategroup->documents;
                if (isset($documents))
                    foreach ($documents as $document)
                        $result->push($document);
            }
        }
        return $result->unique()->sortBy('id');
    }

    public function scopeDelegateGroup()
    {
        $prefix = '';
        $scope = '';
        if (isset($this->delegategroups))
        {
            $delegategroups = $this->delegategroups;
            foreach($delegategroups as $delegategroup)
            {
                $scope .= $prefix . $delegategroup->display_name;
                $prefix = ', ';
            }
        }
        return $scope;
    }

}
