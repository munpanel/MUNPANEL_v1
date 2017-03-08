<?php
/**
 * Copyright (C) Console iT
 * This file is part of MUNPANEL System.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * Developed by Adam Yi <xuan@yiad.am>
 */

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
    /**
     * Get user instance using id.
     *
     * @param int $id id of user
     * @result User the user instance
     */
    public function getUser($id)
    {
        return User::findOrFail($id);
    }
    
    /**
     * Get card instance using id.
     *
     * @param int $id id of card
     * @result Card the card instance
     */
    public function getCard($id)
    {
        return Card::findOrFail($id);
    }

    /**
     * Get specific instance (delegate/volunteer/observer/dais/etc.) using user id.
     *
     * @param int $id id of user
     * @result Object the specific instance
     */
    public function getSpecific($id)
    {
        return User::findOrFail($id)->specific();
    }

    /**
     * Get school instance using id.
     *
     * @param int $id id of school
     * @result School the school instance
     */
    public function getSchool($id)
    {
        return School::findOrFail($id);
    }

    /**
     * Get committee instance using id.
     *
     * @param int $id id of committee
     * @result Committee the committee instance
     */   
    public function getCommittee($id)
    {
        return Committee::findOrFail($id);
    }
    //
}
