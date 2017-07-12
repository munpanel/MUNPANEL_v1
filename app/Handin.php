<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * Developed by Adam Yi <xuan@yiad.am>
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Handin extends Model
{
    protected $table='handins';
    protected $fillable = ['reg_id', 'nation_id', 'assignment_id', 'content', 'handin_type', 'remark']; 

    public function committee() 
    {
        return $this->belongsTo('App\Committee');
    }

    public function reg() 
    {
        return $this->belongsTo('App\Reg');
    }
	
    public function nation()
    {
        return $this->belongsTo('App\Nation');
    }

    public function assignment()
    {
        return $this->belongsTo('App\Assignment');
    }

    public function nameAndInfo()
    {
        $texts = $this->assignment->title;
        if ($this->handin_type == 'upload') return $texts;
        $texts .= ' (';
        if ($this->handin_type == 'json')
        {
            if (!is_object(json_decode($this->content)))
            {
                $texts .= 'JSON 错误)';
                return $texts;
            }
            $content = (array)json_decode($this->content);
            unset($content['_token']);
            unset($content['handin']);
            unset($content['form']);
            $all = count($content);
            $notnull = count(array_filter($content));
            if ($all != $notnull)
                $texts .= $all . ' 项中作答 '.$notnull . ' 项)';
            else
                $texts .= $all . ' 项全部完成)';
            return $texts;
        }
        if ($this->handin_type == 'text') return $texts;
    }
}
