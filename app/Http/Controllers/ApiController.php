<?php

namespace App\Http\Controllers;

use App\Committee;
use App\School;
use App\Delegate;
use App\Volunteer;
use App\Observer;
use App\User;
use App\Assignment;
use App\Handin;
use App\Document;
use App\Card;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getUser($id)
    {
        return User::findOrFail($id);
    }
    
    public function getCard($id)
    {
        return Card::findOrFail($id);
    }

    public function getSpecific($id)
    {
        return User::findOrFail($id)->specific();
    }

    public function getSchool($id)
    {
        return School::findOrFail($id);
    }
    
    public function getCommittee($id)
    {
        return Committee::findOrFail($id);
    }
    //
}
