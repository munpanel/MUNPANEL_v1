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

use App\Nation;
use App\School;
use App\Delegate;
use App\User;
use App\Reg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Config;

class RoleAllocController extends Controller
{
    /**
     * Lock the seat allocation of a committee
     *
     * @param boolean $confirm whether to lock or to prompt an warning
     * @return \Illuminate\Http\Response
     */
    public function lockAlloc($confirm = false)
    {
        if (Reg::current()->type != 'dais')
            return 'Error';
        if ($confirm)
        {
            Reg::current()->dais->committee->is_allocated = true;
            Reg::current()->dais->committee->save();
            return redirect(mp_url('/roleList'));
        }
        else
        {
            return view('warningDialogModal', ['danger' => false, 'msg' => "您将要完成并锁定本委员会的国家分配，此操作将不可撤销。确实要继续吗？", 'target' => mp_url("/dais/lockAlloc/true")]);
        }
    }

    public static function delegates()
    {
        $reg = Reg::current();
        if ($reg->type == 'ot' && $reg->can('assign-roles'))
            return Delegate::all();
        if ($reg->type == 'dais')
            return $reg->dais->committee->delegates;
        if ($reg->type == 'interviewer')
        {
            return Delegate::where(function($query) {
                $query->whereHas('interviews', function($query) {
                    $query->where('interviewer_id', '=', Reg::currentID())->where('status', '=', 'passed');
                });
            })->orWhere(function($query) {
                $query->whereHas('interviews', function($query) {
                    $query->where('interviewer_id', '=', Reg::currentID())->where('status', '=', 'exempted');
                });
            })->get();
        }
        return collect();
    }

    public static function nations()
    {
        $reg = Reg::current();
        if ($reg->type == 'ot' && $reg->can('assign-roles'))
            return Nation::all();
        if ($reg->type == 'dais')
            return $reg->dais->committee->nations;
        if ($reg->type == 'interviewer')
        {
            return Nation::whereHas('committee', function($query) {
                $query->whereHas('delegates.interviews', function($query) {
                    $query->where('interviewer_id', '=', Reg::currentID())->where('status', '=', 'passed');
                })->orWhereHas('delegates.interviews', function($query) {
                    $query->where('interviewer_id', '=', Reg::currentID())->where('status', '=', 'exempted');
                });
            })->get();
        }
        return collect();
    }

    /**
     * Remove a delegate from its original seat
     *
     * @param int $id the id of the delegate
     * return \Illuminate\Http\Response
     */
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
        return redirect(mp_url('/roleAlloc'));
    }

    /**
     * Handle the HTTP request to ssign a delegate to a new seat
     *
     * @param Request $request
     * @param int $id the id of the delegate
     * @return \Illuminate\Http\Response
     */
    public function addDelegate(Request $request, $id)
    {
        if (RoleAllocController::addAssign(Delegate::findOrFail($id), Nation::findOrFail($request->nation)))
            return 'success';
        return 'error';
        //return redirect(mp_url('/roleAlloc'));
    }

    /**
     * Assign a delegate to a new seat
     *
     * @param Request $request
     * @param int $id the id of the delegate
     * @return \Illuminate\Http\Response
     */
    public static function addAssign($delegate, $nation, $partner = true)
    {
        //$delegate->nation_id = $nation->id;
        if (!$delegate->canAssignSeats())
            return false;
        if ($delegate->committee_id != $nation->committee_id)
            return false;
        $max = $nation->committee->maxAssignList;
        if ($delegate->assignedNations->count() >= $max && $max != -1)
            return false;
        if ($max == 1)
        {
            $delegate->nation_id = $nation->id;
            $delegate->save();
        }
        else if (!$delegate->assignedNations->contains($nation))
            $delegate->assignedNations()->attach($nation->id);
        if ($partner && isset($delegate->partner_user_id))
        {
            $partner = $delegate->partner;
            return RoleAllocController::addAssign($partner, $nation, false);
        }
        return true;
    }

    /**
     * Clear out one seat so that all delegates assigned to it are removed
     * from this seat.
     *
     * @param int $id the id of the seat
     * @return \Illuminate\Http\Response
     */
    public function freeNation($id)
    {
        $nation = Nation::findOrFail($id);
        $delegates = $nation->delegates;
        foreach($delegates as $delegate)
        {
            if ($delegate->nation_locked)
                return 'LOCKED';
        }
        foreach($delegates as $delegate)
        {
            if ($delegate->canAssignSeats())
            {
                $delegate->nation_id = null;
                $delegate->save();
                $nation->assignedDelegates()->detach($delegate->reg_id);
            }
        }
        return redirect(mp_url('/roleAlloc'));
    }

    /**
     * Display the details modal of a nation.
     *
     * @param int $id the id of the nation
     * @return \Illuminate\Http\Response
     */
    public function nationDetailsModal($id)
    {
        if (Reg::current()->type != 'dais')
            return "Error";
        if ($id == 'new')
        {
            $nation = new Nation;
            $nation->name = 'New Nation';
            $nation->committee_id = Reg::current()->dais->committee_id;
            $nation->conpetence = 1;
            $nation->veto_power = 0;
            $nation->save();
        }
        else
            $nation = Nation::findOrFail($id);
        return view('dais.nationDetailsModal', ['nation' => $nation]);
    }

    /**
     * Update a property of a nation
     *
     * @param Request $request
     * @param int $id the id of the nation updated
     * @return void
     */
    public function updateNation(Request $request, $id)
    {
        if (Reg::current()->type != 'dais')
            return 'Error';
        $nation = Nation::findOrFail($id);
        $name = $request->get('name');
        $value = $request->get('value');
        $nation->$name = $value;
        $nation->save();
    }

    /**
     * Delete a nation from database
     *
     * @param Request $request
     * @param int $id the id of the nation to be removed
     * @param boolean $confirm whether to remove the nation or to show a prompt
     * @return void
     */
    public function deleteNation(Request $request, $id, $confirm = false)
    {
        if (Reg::current()->type != 'dais')
            return 'Error';
        if ($confirm)
        {
            Nation::destroy($id);
            return redirect(mp_url('/roleAlloc'));
	}
	else
	{
            $name = Nation::findOrFail($id)->name;
            return view('warningDialogModal', ['danger' => false, 'msg' => "您将要删除国家$name 。确实要继续吗？", 'target' => mp_url("/dais/delete/nation/$id/true")]);
	}
    }

    /**
     * Link two delegates as partner
     *
     * @param int $id1 the id of the first delegate
     * @param int $id2 the id of the second delegate
     * @return \Illuminate\Http\Response
     */
    public function linkPartner($id1, $id2)
    {
        if (Reg::current()->type == 'dais')
            $cid = Reg::current()->dais->committee->id;
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
        return redirect(mp_url('/roleAlloc'));
    }

    /**
     * Show the modal in which dais can link partners.
     *
     * @return void
     */
    public function linkPartnerModal()
    {
        return view('dais.linkPartnerModal');
    }

    /**
     * Show the biz card of a delegate.
     *
     * @param int $id the id of the delegate
     * @return \Illuminate\Http\Response
     */
    public function getDelegateBizcard($id)
    {
        $del = Delegate::findOrFail($id);
        return view('delegateBizCard', ['delegate' => $del]);
    }

    public function updateSeat(Request $request)
    {
        $delegate = Delegate::findOrFail($request->id);
        if ($delegate->reg_id == Reg::currentID())
        {
            if (!$delegate->seat_locked && isset($request->seatSelect) && $delegate->assignedNations->contains($request->seatSelect))
            {
                $delegate->nation_id = $request->seatSelect;
                $delegate->save();
            }
        }
        else if ($delegate->canAssignSeats() && (!$delegate->seat_locked))
        {
            $delegate->assignedNations()->sync($request->seats);
            if (!$delegate->assignedNations->contains($delegate->nation_id))
            {
                $delegate->nation_id = null;
                $delegate->save();
                //TODO: SMS
            }
        }
    }

    public function sendSMS($id, $confirm = false)
    {
        $delegate = Delegate::findOrFail($id);
        if (!$delegate->canAssignSeats())
            return 'error';
        if ($confirm)
        {
            $delegate->reg->user->sendSMS('感谢您报名'.Reg::currentConference()->name.'，我们现已更新了您的可选席位列表，烦请登陆 MUNPANEL 系统查看详情并选择自己的意向席位。');
        }
        else
        {
            return view('warningDialogModal', ['danger' => false, 'msg' => "系统将发送一条短信通知".$delegate->reg->name()."可选席位列表更新。确实要继续吗？", 'target' => mp_url("/dais/seatSMS.modal/".$id."/true"), 'ajax' => true]);
        }
    }
}
