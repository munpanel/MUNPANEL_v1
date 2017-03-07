<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reg extends Model
{
    protected $fillable = ['user_id','conference_id','school_id','type','status','gender','reginfo','committee_id','nation_id','partner_user_id','roommate_user_id'];

    public function conference() {
        return $this->belongsTo('App\Conference');
    }

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

    public function cards() {
        return $this->hasMany('App\Card', 'user_id');
    }

    public function events() {
        return $this->hasMany('App\Event');
    }

    public function partner() {
        return $this->belongsTo('App\User', 'partner_user_id');
    }
    
    public function roommate() {
        return $this->belongsTo('App\User', 'roommate_user_id'); 
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

    public function addEvent($type, $content)
    {
        $event = new Event;
        $event->eventtype_id = $type;
        $event->content = $content;
        $event->reg_id = $this->id;
        $event->save();
        return $event;
    }
}
