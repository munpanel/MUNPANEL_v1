<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

namespace App\Http\Controllers;

use App\Nation;
use App\School;
use App\Delegate;
use App\User;
use App\Reg;
use App\Committee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Config;

class RoleAllocController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

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
        $assignOptions = json_decode(Reg::currentConference()->option('seat_assigners'));
        if (!is_object($assignOptions))
            return eloquent_collect();
        if ($assignOptions->overrule)
        {
            $assignCommittees = $reg->assignCommittees;
            if (in_array($reg->type, ['ot', 'dais', 'interviewer']) && $assignCommittees->isNotEmpty())
            {
                $result = eloquent_collect();
                foreach ($assignCommittees as $committee)
                    $result = $result->merge($committee->delegates);
                return $result;
            }
        }
        if ($assignOptions->ot && $reg->type == 'ot' && $reg->can('assign-roles'))
            return Reg::currentConference()->delegates;
        if ($assignOptions->dais && $reg->type == 'dais')
            return $reg->dais->committee->delegates;
        if ($assignOptions->interviewer && $reg->type == 'interviewer')
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
        return eloquent_collect();
    }

    public static function nations()
    {
        $reg = Reg::current();
        $assignOptions = json_decode(Reg::currentConference()->option('seat_assigners'));
        if (!is_object($assignOptions))
            return eloquent_collect();
        if ($assignOptions->overrule)
        {
            $assignCommittees = $reg->assignCommittees;
            if (in_array($reg->type, ['ot', 'dais', 'interviewer']) && $assignCommittees->isNotEmpty())
            {
                $result = eloquent_collect();
                foreach ($assignCommittees as $committee)
                    $result = $result->merge($committee->nations);
                return $result;
            }
        }
        if ($assignOptions->ot && $reg->type == 'ot' && $reg->can('assign-roles'))
        {
            $committees = Committee::where('conference_id', Reg::currentConferenceID())->get()->pluck(['id']);
            return Nation::whereIn('committee_id', $committees)->get();
        }
        if ($assignOptions->dais && $reg->type == 'dais')
            return $reg->dais->committee->nations;
        if ($assignOptions->interviewer && $reg->type == 'interviewer')
        {
            $committees = Committee::where('conference_id', Reg::currentConferenceID())->get()->pluck(['id']);
            return Nation::whereIn('committee_id', $committees)->get();
            // now interviewers can assign all nations
            return Nation::whereHas('committee', function($query) {
                $query->whereHas('delegates.interviews', function($query) {
                    $query->where('interviewer_id', '=', Reg::currentID())->where('status', '=', 'passed');
                })->orWhereHas('delegates.interviews', function($query) {
                    $query->where('interviewer_id', '=', Reg::currentID())->where('status', '=', 'exempted');
                });
            })->get();
        }
        return eloquent_collect();
    }

    /**
     * Remove a delegate from its original seat
     *
     * @param int $id the id of the delegate
     * return \Illuminate\Http\Response
     */
    public function removeDelegate($id)
    {
        $delegate = Delegate::findOrFail($id);
        $nation = $delegate->nation;
        $delegate->nation_id = null;
        $delegate->seat_locked = false;
        $delegate->save();
        $partner = $delegate->partner;
        if (is_object($partner))
        {
            $partner->nation_id = null;
            $partner->seat_locked = false;
            $partner->save();
        }
        if (is_object($nation))
        {
            $nation->status = 'open';
            $nation->save();
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
    public function addDelegate(Request $request, $id, $action = 'assign')
    {
        $delegate = Delegate::findOrFail($id);
        $nation = Nation::find($request->nation);
        switch ($action)
        {
            case 'assign':
                if (!is_object($nation))
                    return 'error';
                if ($delegate->committee_id != $nation->committee_id)
                    return 'prompt';
            case 'doAssign':
                if (!is_object($nation))
                    return 'error';
                if (RoleAllocController::addAssign($delegate, $nation))
                    return 'success';
                return 'error';
            case 'modal':
                return view('dais.roleAllocInconsistModal', ['delegate' => $delegate]);
            default:
                return 'error';
        }
        /*if (RoleAllocController::addAssign(Delegate::findOrFail($id), Nation::findOrFail($request->nation)))
            return 'success';
        return 'error';*/
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
        if (!$delegate->canAssignSeats($nation))
            return false;
//            return false;
        /*if ($delegate->committee_id != $nation->committee_id)
            return false;
        if ($delegate->assignedNations->count() >= $max && $max != -1)
            return false;*/
        $ret = true;
        $max = $nation->committee->maxAssignList;
        if ($max == 1)
        {
            $delegate->nation_id = $nation->id;
            $delegate->save();
        }
        else if (!$delegate->assignedNations->contains($nation))
            $delegate->assignedNations()->attach($nation->id);
        if ($partner)
            $partner = $delegate->partner;
        if (is_object($partner))
        {
            $partner = $delegate->partner;
            $ret = RoleAllocController::addAssign($partner, $nation, false);
        }
        if ($max == 1)
            RoleAllocController::lockSeat($delegate->reg_id);
        $delegate->reg->addEvent('role_assigned', '{"name":"'.Reg::current()->name().'", "role":"'.$nation->displayName(true, 2).'"}');
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
        $delegates = $nation->assignedDelegates;
        foreach($delegates as $delegate)
        {
            if ($delegate->seat_locked && $delegate->nation_id == $id)
                return 'LOCKED';
        }
        foreach($delegates as $delegate)
        {
            if ($delegate->canAssignSeats())
            {
                if ($delegate->nation_id == $id) {
                    $delegate->nation_id = null;
                    $delegate->save();
                }
                $nation->assignedDelegates()->detach($delegate->reg_id);
                $delegate->reg->addEvent('role_altered', '{"name":"'.Reg::current()->name().'"}');
            }
        }
        $delegates = $nation->delegates;
        foreach ($delegates as $delegate)
        {
            $delegate->nation_id = null;
            $delegate->save();
        }
        $nation->status = 'open';
        $nation->save();
        return 'success';
        //return redirect(mp_url('/roleAlloc'));
    }

    /**
     * Display the details modal of a nation.
     *
     * @param int $id the id of the nation
     * @return \Illuminate\Http\Response
     */
    public function nationDetailsModal($id)
    {
        if (Reg::current()->type == 'ot') {
            if (!Reg::current()->can('edit-nations'))
                return "Error";
        } elseif (Reg::current()->type != 'dais') {
            return "Error";
        }
        if ($id == 'new')
        {
            $nation = new Nation;
            $nation->name = 'New Nation';
            $nation->committee_id = Reg::current()->dais->committee_id;
            $nation->conference_id = Reg::currentConferenceID();
            $nation->conpetence = 1;
            $nation->veto_power = 0;
            $nation->save();
        }
        else
            $nation = Nation::findOrFail($id);
        $com = array();
        $committees = Committee::where('conference_id', Reg::currentConferenceID())->get();
        foreach ($committees as $committee)
        {
            $tmp = array();
            $tmp['value'] = $committee->id;
            $tmp['text'] = $committee->name;
            $com[] = $tmp;
        }
        return view('dais.nationDetailsModal', ['nation' => $nation, 'committeesJSON' => json_encode($com)]);
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
        if (Reg::current()->type == 'ot') {
            if (!Reg::current()->can('edit-nations'))
                return "Error";
        } elseif (Reg::current()->type != 'dais') {
            return "Error";
        }
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
        if (Reg::current()->type == 'ot') {
            if (!Reg::current()->can('edit-nations'))
                return "Error";
        } elseif (Reg::current()->type != 'dais') {
            return "Error";
        }
        $nation = Nation::findOrFail($id);
        if ($nation->status != 'open')
            return "Error"; //TODO: change this
        if ($confirm)
        {
            Nation::destroy($id);
            return redirect(mp_url('/roleAlloc'));
	}
	else
	{
            $name = $nation->name;
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
                $nation = Nation::findOrFail($request->seatSelect);
                if ($nation->status != 'open')
                    return 'error';
                if (is_object($delegate->nation))
                {
                    $delegate->nation->status = 'open';
                    $delegate->nation->save();
                }
                $delegate->nation_id = $request->seatSelect;
                $delegate->save();
                $delegate->reg->addEvent('role_selected', '{"role":"'.$nation->displayName(true, 2).'"}');
                if (is_object($delegate->partner))
                {
                    $delegate->partner->nation_id = $request->seatSelect;
                    $delegate->partner->save();
                    $delegate->partner->reg->addEvent('role_selected', '{"role":"'.$nation->displayName(true, 2).'"}');
                }
                $nation->status = 'selected';
                $nation->save();
            }
        }
        else if ($delegate->canAssignSeats() && (!$delegate->seat_locked))
        {
            $delegate->assignedNations()->sync($request->seats);
            $delegate->reg->addEvent('role_altered', '{"name":"'.Reg::current()->name().'"}');
            if (is_object($delegate->partner))
            {
                $delegate->partner->assignedNations()->sync($request->seats);
                $delegate->partner->reg->addEvent('role_altered', '{"name":"'.Reg::current()->name().'"}');
            }
            if (isset($delegate->nation_id) && (!$delegate->assignedNations->contains($delegate->nation_id)))
            {
                $nation = $delegate->nation;
                $nation->status = 'open';
                $nation->save();
                $delegate->nation_id = null;
                $delegate->save();
                if (is_object($delegate->partner))
                {
                    $partner = $delegate->partner;
                    $partner->nation_id = null;
                    $partner->save();
                }
            }
        }
        else
            return 'error';
        return 'success';
    }

    public static function lockSeat($id)
    {
        $delegate = Delegate::findOrFail($id);
        if (!$delegate->canAssignSeats())
            return 'error';
        $delegate->seat_locked = true;
        if ($delegate->committee_id != $delegate->nation->committee_id)
            $delegate->reg->addEvent('committee_moved', '{"name":" MUNPANEL 自动","committee":"'.$delegate->nation->committee->display_name.'"}');
        $delegate->committee_id = $delegate->nation->committee_id;
        $delegate->save();
        $reg = $delegate->reg;
        $reg->addEvent('role_locked', '{"name":"'.Reg::current()->name().'"}');
        $partner = $delegate->partner;
        if (is_object($partner))
        {
            $partner->seat_locked = true;
            $partner->committee_id = $delegate->partner->nation->committee_id;
            $partner->save();
            $partnerReg = $partner->reg;
            $partnerReg->addEvent('role_locked', '{"name":"'.Reg::current()->name().'"}');
            if ((!isset($partnerReg->order_id)) && Reg::currentConference()->option('reg_order_create_time') == 'seatLock' && isset($partnerReg->accomodate))
                $partnerReg->createConfOrder();
        }
        //$delegate->nation->setLock();
        $nation = $delegate->nation;
        $nation->status = 'locked';
        $nation->save();
        if ((!isset($reg->order_id)) && Reg::currentConference()->option('reg_order_create_time') == 'seatLock' && isset($reg->accomodate))
            $reg->createConfOrder();
        return 'success';
    }

    public function unlockSeat($id)
    {
        $delegate = Delegate::findOrFail($id);
        if (!$delegate->canAssignSeats())
            return 'error';
        $delegate->seat_locked = false;
        $delegate->committee_id = $delegate->nation->committee_id;
        $delegate->save();
        $delegate->reg->addEvent('role_unlocked', '{"name":"'.Reg::current()->name().'"}');
        if (is_object($delegate->partner))
        {
            $delegate->partner->seat_locked = false;
            $delegate->partner->committee_id = $delegate->partner->nation->committee_id;
            $delegate->partner->save();
            $delegate->partner->reg->addEvent('role_unlocked', '{"name":"'.Reg::current()->name().'"}');
        }
        //$delegate->nation->setLock();
        $delegate->nation->status = 'selected';
        $delegate->nation->save();
        return 'success';
    }

    public function sendSMS($id, $confirm = false)
    {
        $delegate = Delegate::findOrFail($id);
        if (!$delegate->canAssignSeats())
            return 'error';
        if ($confirm)
        {
            $delegate->reg->user->sendSMS('感谢您报名'.Reg::currentConference()->name.'，我们现已更新了您的可选席位列表，您现共有'.$delegate->assignedNations->count().'个可选席位，烦请登陆 MUNPANEL 系统查看详情并选择自己的意向席位。');
            if (is_object($delegate->partner))
                $delegate->partner->reg->user->sendSMS('感谢您报名'.Reg::currentConference()->name.'，我们现已更新了您的可选席位列表，您现共有'.$delegate->partner->assignedNations->count().'个可选席位，烦请登陆 MUNPANEL 系统查看详情并选择自己的意向席位。');
        }
        else
        {
            return view('warningDialogModal', ['danger' => false, 'msg' => "系统将发送一条短信通知".$delegate->reg->name()."可选席位列表更新。确实要继续吗？", 'target' => mp_url("/dais/seatSMS.modal/".$id."/true"), 'ajax' => 'get']);
        }
    }
}
