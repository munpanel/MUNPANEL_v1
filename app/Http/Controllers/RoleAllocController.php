<?php

namespace App\Http\Controllers;

use App\Committee;
use App\School;
use App\Delegate;
use App\User;
use App\Nation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Config;

class RoleAllocController extends Controller
{
    public function lockAlloc()
    {
        
    }

    public function removeDelegate($id)
    {
        $user = User::findOrFail($id);
        $delegate = $user->delegate;
        $delegate->nation_id = null;
        $delegate->save();
        if (isset($delegate->partner_user_id))
        {
            $partner = $delegate->partner->delegate;
            $partner->nation_id = null;
            $partner->save();
        }
        return redirect(secure_url('/roleAlloc'));
    }

    public function freeNation($id)
    {
        $nation = Nation::findOrFail($id);
        $delegates = $nation->delegates;
        foreach($delegates as $delegate)
        {
            $delegate->nation_id = null;
            $delegate->save();
        }
        return redirect(secure_url('/roleAlloc'));
    }
}
