<?php

namespace App\Http\Controllers;

use App\Nation;
use App\School;
use App\Delegate;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Config;

class RoleAllocController extends Controller
{
    public function lockAlloc($confirm = false)
    {
        if (Auth::user()->type != 'dais')
            return 'Error';
        if ($confirm)
        {
            Auth::user()->dais->committee->is_allocated = true;
            Auth::user()->dais->committee->save();
            return redirect(secure_url('/roleList'));
        }
        else
        {
            return view('warningDialogModal', ['danger' => false, 'msg' => "您将要完成并锁定本委员会的国家分配，此操作将不可撤销。确实要继续吗？", 'target' => secure_url("/dais/lockAlloc/true")]);
        }
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

    public function addDelegate(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $nation = Nation::findOrFail($request->nation);
        $delegate = $user->delegate;
        $delegate->nation_id = $nation->id;
        $delegate->save();
        if (isset($delegate->partner_user_id))
        {
            $partner = $delegate->partner->delegate;
            $partner->nation_id = $nation->id;
            $partner->save();
        }
        return 'success';
        //return redirect(secure_url('/roleAlloc'));
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

    public function nationDetailsModal($id)
    {
        if (Auth::user()->type != 'dais')
            return "Error";
        if ($id == 'new')
        {
            $nation = new Nation;
            $nation->name = 'New Nation';
            $nation->committee_id = Auth::user()->dais->committee_id;
            $nation->conpetence = 1;
            $nation->veto_power = 0;
            $nation->save();
        }
        else
            $nation = Nation::findOrFail($id);
        return view('dais.nationDetailsModal', ['nation' => $nation]);
    }

    public function updateNation(Request $request, $id)
    {
        if (Auth::user()->type != 'dais')
            return 'Error';
        $nation = Nation::findOrFail($id);
        $name = $request->get('name');
        $value = $request->get('value');
        $nation->$name = $value;
        $nation->save();
    }

    public function deleteNation(Request $request, $id, $confirm = false)
    {
        if (Auth::user()->type != 'dais')
            return 'Error';
        if ($confirm)
        {
            Nation::destroy($id);
            return redirect(secure_url('/roleAlloc'));
	}
	else
	{
            $name = Nation::findOrFail($id)->name;
            return view('warningDialogModal', ['danger' => false, 'msg' => "您将要删除国家$name 。确实要继续吗？", 'target' => secure_url("/dais/delete/nation/$id/true")]);
	}
    }

    public function linkPartner($id1, $id2)
    {
        if (Auth::user()->type == 'dais')
            $cid = Auth::user()->dais->committee->id;
        else
            return 'Error';
        $del1 = Delegate::findOrFail($id1);
        $del2 = Delegate::findOrFail($id2);
        if ($del1->committee->id != $cid || $del2->committee->id != $cid)
            return "UID对应代表并非您的委员会，请确认。";
        if (isset($del1->partner_user_id) || isset($del2->partner_user_id))
            return "用户已有搭档，无法重新分配。";
        $del1->partner_user_id = $id2;
        $del2->partner_user_id = $id1;
        $del1->partnername = $del2->user->name;
        $del2->partnername = $del1->user->name;
        $del1->save();
        $del2->save();
        return redirect(secure_url('/roleAlloc'));
    }

    public function linkPartnerModal()
    {
        return view('dais.linkPartnerModal');
    }

    public function getDelegateBizcard($id)
    {
        $del = Delegate::findOrFail($id);
        return view('delegateBizCard', ['delegate' => $del]);
    }
}
