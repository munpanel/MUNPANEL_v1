<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Yajra\Datatables\Datatables;
use App\User;
use App\Reg;
use App\Delegate;
use App\Volunteer;
use App\School;
use App\Dais;
use App\Orgteam;
use App\Committee;
use App\Assignment;
use App\Handin;
use App\Nation;
use App\Good;
use App\Order;
use App\Document;
use Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DatatablesController extends Controller //To-Do: Permission Check
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
     * (Deprecated) Show registration datatables json
     *
     * @return string JSON of registrations
     */
    public function registrations()
    {
        $user = Reg::current();
        if ($user->type == 'school') {
            $result = new Collection;
            $delegates = Delegate::with(['school' => function($q) {$q->select('name', 'id');}, 'user' => function($q) {$q->select('name', 'id');}, 'committee' => function($q) {$q->select('name', 'id');}])->where('school_id', $user->school->id)->get(['user_id', 'school_id', 'committee_id', 'status', 'partnername']);//->select(['user_id', 'name', 'school', 'committee', 'partnername']);
            foreach ($delegates as $delegate)
            {
                if ($delegate->status == 'reg')
                    $status = '等待学校审核';
                else if ($delegate->status == 'sVerified')
                    $status = '等待组委审核';
                 else if ($delegate->status == 'oVerified')
                    $status = '待缴费';
                 else if ($delegate->status == 'paid')
                    $status = '成功';
                if ($delegate->partnername == '')
                    $partner = '无';
                else
                    $partner = $delegate->partnername;
                if (Config::get('munpanel.registration_school_changable'))
                {
                    if ($delegate->status == 'reg')
                        $status = '<a href="#" class="approval-status" data-id="'. $delegate->user_id .'"><i class="fa fa-check text-success text-active"></i><i class="fa fa-times text-danger text"></i></a>';
                    else
                        $status = '<a href="#" class="approval-status active" data-id="'. $delegate->user_id .'"><i class="fa fa-check text-success text-active"></i><i class="fa fa-times text-danger text"></i></a>';
                }
                $result->push([
                    'details' => '<a href="reg.modal/'. $delegate->user_id .'" data-toggle="ajaxModal" id="'. $delegate->user_id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>',
                    'name' => $delegate->user->name,
                    'committee' => $delegate->committee->name,
                    'partner' => $partner,
                    'approval' => $status,
                ]);
            }
            $volunteers = Volunteer::with(['school' => function($q) {$q->select('name', 'id');}, 'user' => function($q) {$q->select('name', 'id');}])->where('school_id', $user->school->id)->get(['user_id', 'school_id', 'status']);
            foreach ($volunteers as $volunteer)
            {
                if ($volunteer->status == 'reg')
                    $status = '等待学校审核';
                else if ($volunteer->status == 'sVerified')
                    $status = '等待组委审核';
                 else if ($volunteer->status == 'oVerified')
                    $status = '待缴费';
                 else if ($volunteer->status == 'paid')
                    $status = '成功';
                if (Config::get('munpanel.registration_school_changable'))
                {
                    if ($volunteer->status == 'reg')
                        $status = '<a href="#" class="approval-status" data-id="'. $volunteer->user_id .'"><i class="fa fa-check text-success text-active"></i><i class="fa fa-times text-danger text"></i></a>';
                    else
                        $status = '<a href="#" class="approval-status active" data-id="'. $volunteer->user_id .'"><i class="fa fa-check text-success text-active"></i><i class="fa fa-times text-danger text"></i></a>';
                }
                $result->push([
                    'details' => '<a href="reg.modal/'. $volunteer->user_id .'" data-toggle="ajaxModal" id="'. $volunteer->user_id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>',
                    'name' => $volunteer->user->name,
                    'committee' => "志愿者",
                    'partner' => "无",
                    'approval' => $status,
                ]);

            }
            //TO-DO: Observers
        }
        else if ($user->type=='ot'){
            if (!Reg::current()->can('view-regs'))
                return "ERROR";
            $result = new Collection;
            $delegates = Delegate::with(['school' => function($q) {$q->select('name', 'id', 'user_id');}, 'user' => function($q) {$q->select('name', 'id');}, 'committee' => function($q) {$q->select('name', 'id');}])->get(['user_id', 'school_id', 'committee_id', 'status', 'partnername']);//->select(['user_id', 'name', 'school', 'committee', 'partnername']);
            foreach ($delegates as $delegate)
            {
                if ($delegate->partnername == '')
                    $partner = '无';
                else
                    $partner = $delegate->partnername;
                if ($delegate->status == 'paid')
                    $statusbar = 'has-success';
                else if ($delegate->status == 'oVerified')
                    $statusbar = 'has-warning';
                else if ($delegate->status == 'sVerified')
                    $statusbar = '';
                else
                    $statusbar = 'has-error';
                if ($delegate->status == 'paid' && (!$user->can('approve-regs-pay')))
                    $status = '成功';
                else if ($user->can('approve-regs'))
                    $status = '<div class="status-select '.$statusbar.'" uid="'. $delegate->user_id .'">'.$delegate->status."</div>";
                else if ($delegate->status == 'reg')
                    $status = '等待学校审核';
                else if ($delegate->status == 'sVerified')
                    $status = '等待组委审核';
                 else if ($delegate->status == 'oVerified')
                    $status = '待缴费';
                 else if ($delegate->status == 'paid')
                    $status = '成功';
                $school = $delegate->school->name;
                if ($delegate->school->user_id == 1)
                    $school .= '(非成员校)';
                $result->push([
                    'details' => '<a href="ot/regInfo.modal/'. $delegate->user_id .'" data-toggle="ajaxModal" id="'. $delegate->user_id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>',
                    'name' => $delegate->user->name,
                    'school' => $school,
                    'committee' => $delegate->committee->name,
                    'partner' => $partner,
                    'status' => $status,
                ]);
            }
            $volunteers = Volunteer::with(['school' => function($q) {$q->select('name', 'id', 'user_id');}, 'user' => function($q) {$q->select('name', 'id');}])->get(['user_id', 'school_id', 'status']);
            foreach ($volunteers as $volunteer)
            {
                if ($volunteer->status == 'paid')
                    $statusbar = 'has-success';
                else if ($volunteer->status == 'oVerified')
                    $statusbar = 'has-warning';
                else if ($volunteer->status == 'sVerified')
                    $statusbar = '';
                else
                    $statusbar = 'has-error';
                if ($volunteer->status == 'paid' && (!$user->can('approve-regs-pay')))
                    $status = '成功';
                else if ($user->can('approve-regs'))
                    $status = '<div class="status-select '.$statusbar.'" uid="'. $volunteer->user_id .'">'.$volunteer->status."</div>";
                else if ($volunteer->status == 'reg')
                    $status = '等待学校审核';
                else if ($volunteer->status == 'sVerified')
                    $status = '等待组委审核';
                 else if ($volunteer->status == 'oVerified')
                    $status = '待缴费';
                 else if ($volunteer->status == 'paid')
                    $status = '成功';
                $school = $volunteer->school->name;
                if ($volunteer->school->user_id == 1)
                    $school .= '(非成员校)';
                $result->push([
                    'details' => '<a href="reg.modal/'. $volunteer->user_id .'" data-toggle="ajaxModal" id="'. $volunteer->user_id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>',
                    'name' => $volunteer->user->name,
                    'school' => $school,
                    'committee' => "志愿者",
                    'partner' => "无",
                    'status' => $status,
                ]);

            }
            //TO-DO: Observers
        }
        else
            return "Error";
        return Datatables::of($result)->make(true);
    }

    /**
     * Show registration datatables json
     *
     * @return string JSON of registrations
     */
    public function reg2Table()
    {
        $user = Reg::current();
        $conf = Reg::currentConferenceID();
        $type = ['delegate', 'volunteer', 'observer'];
        $result = new Collection;
        if (Dais::where('conference_id', $conf)->whereIn('status', ['sVerified', 'oVerified'])->count() > 0) $type[] = 'dais';
        if (Orgteam::where('conference_id', $conf)->whereIn('status', ['sVerified', 'oVerified'])->count() > 0) $type[] = 'ot';
        if (in_array($user->type, ['ot', 'dais', 'teamadmin']))
        {
            if ($user->type != 'teamadmin' && !Reg::current()->can('view-regs'))
                return "ERROR";
            // 过滤结果: 只保留 delegate, observer 和 volunteer
            $regs = Reg::where('conference_id', Reg::currentConferenceID());
            if ($user->type == 'ot')
                $regs = $regs->whereIn('type', $type);
            else
                $regs = $regs->whereIn('type', ['delegate', 'volunteer', 'observer']);
            if ($user->type == 'teamadmin')
                $regs = $regs->where('school_id', $user->school_id);
            $regs = $regs->with(['user' => function($q) {$q->select('name', 'id');}])->with(['school' => function($q) {$q->select('name', 'id');}])->get(['id', 'user_id', 'school_id', 'type', 'enabled', 'reginfo']);
            $regs->where('type', 'delegate')->load('delegate', 'delegate.committee', 'delegate.delegategroups', 'delegate.nation', 'delegate.interviews', 'delegate.assignedNations', 'delegate.conference');
            $regs->where('type', 'volunteer')->load('volunteer');
            $regs->where('type', 'observer')->load('observer');
            $regs->where('type', 'dais')->load('dais');
            $regs->where('type', 'ot')->load('ot');
            foreach ($regs as $reg)
            {
                switch ($reg->type)
                {
                    case 'unregistered':
                        $type = '未报名'; break;
                    case 'delegate':
                        $type = '代表'; break;
                    case 'volunteer':
                        $type = '志愿者'; break;
                    case 'observer':
                        $type = '观察员'; break;
                    case 'dais':
                        $type = '学术团队'; break;
                    case 'ot':
                        $type = '组织团队'; break;
                    default:
                        $type = '未知';
                }
                if (null !== $reg->specific())
                {
                    if ($reg->specific()->status == 'paid')
                        $statusbar = 'has-success';
                    else if ($reg->specific()->status == 'oVerified')
                        $statusbar = 'has-warning';
                    else if ($reg->specific()->status == 'sVerified')
                        $statusbar = '';
                    else
                        $statusbar = 'has-error';
                    if ($reg->specific()->status == 'paid' && (!$user->can('approve-regs-pay')))
                        $status = '成功';
                    else if ($user->can('approve-regs'))
                        $status = '<div class="status-select '.$statusbar.'" uid="'. $reg->user_id .'">'.$reg->specific()->status."</div>";
                    else if ($reg->specific()->status == 'reg')
                        $status = '等待学校审核';
                    else if ($reg->specific()->status == 'sVerified')
                        $status = '等待组委审核';
                     else if ($reg->specific()->status == 'oVerified')
                        $status = '待缴费';
                     else if ($reg->specific()->status == 'paid')
                        $status = '成功';
                     else if ($reg->specific()->status == 'init')
                        $status = '报名学测未完成';
                     else if ($reg->specific()->status == 'fail')
                        $status = '审核未通过';
                     if (in_array($reg->type, ['delegate', 'volunteer', 'dais', 'ot']))
                         $status = $reg->specific()->statusText();
                }
                else $status = '报名数据异常';
                if (!$reg->enabled) $status = '已禁用';

                if (in_array($reg->type, ['ot', 'dais']))
                    $detail =  '<a href="ot/daisregInfo.modal/'. $reg->id .'" data-toggle="ajaxModal" id="'. $reg->id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>';
                else
                    $detail =  '<a href="ot/regInfo.modal/'. $reg->id .'" data-toggle="ajaxModal" id="'. $reg->id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>';
                if ($user->type == 'teamadmin') {
                    if ($status == '等待学校审核')
                        $status = '<a href="#" class="approval-status" data-id="'. $reg->id .'"><i class="fa fa-check text-success text-active"></i><i class="fa fa-times text-danger text"></i> 等待学校审核</a>';
                    elseif ($status == '等待组委审核' || $status == '等待组织团队审核')
                        $status = '<a href="#" class="approval-status active" data-id="'. $reg->id .'"><i class="fa fa-check text-success text-active"></i><i class="fa fa-times text-danger text"></i> 等待组织团队审核</a> ';
                    $result->push([
                        'details' => $detail,
                        'id' => $reg->id,
                        'name' => $reg->user->name,
                        'committee' => isset($reg->specific()->committee) ? $reg->specific()->committee->name : (in_array($reg->type, ['dais', 'delegate', 'observer']) ? '未指定' : '不适用'),
                        'type' => $type,
                        'status' => $status,
                    ]);
                } else {
                    $result->push([
                        'details' => $detail,
                        'id' => $reg->id,
                        'name' => $reg->user->name,
                        'school' => $reg->schoolName(),
                        'group' => isset($reg->specific()->delegategroups) ? $reg->specific()->delegateGroupScope(true, 3) : '无',
                        'committee' => isset($reg->specific()->committee) ? $reg->specific()->committee->name : (in_array($reg->type, ['dais', 'delegate', 'observer']) ? '未指定' : '不适用'),
                        'type' => $type,
                        'status' => $status,
                    ]);
                }
            }
        }
        return Datatables::of($result)->make(true);
    }

    /**
     * Show team member datatables json
     *
     * @return string JSON of team members
     */
    public function teamTable()
    {
        $user = Reg::current();
        $conf = 2;
        if ($user->type == 'ot')
        {
            if (false)//(!Reg::current()->can('view-regs'))
                return "ERROR";
            $result = new Collection;
            // 过滤结果: 只保留 delegate, observer 和 volunteer
            $regs = Reg::where('conference_id', Reg::currentConferenceID())->whereIn('type', ['ot', 'dais', 'teamadmin', 'interviewer'])->with(['user' => function($q) {$q->select('name', 'id');}])->get(['id', 'user_id', 'type']);
            foreach ($regs as $reg)
            {
                if (in_array($reg->type, ['ot', 'dais']) && null !== $reg->specific() && $reg->specific()->status != 'success') continue;
                if ($reg->type == 'unregistered')
                    $type = '未报名';
                else if ($reg->type == 'ot')
                    $type = '组织团队';
                else if ($reg->type == 'teamadmin')
                    $type = $reg->teamadmin->school->typeText().'管理';
                else if ($reg->type == 'dais')
                    $type = '学术团队';
                else if ($reg->type == 'interviewer')
                    $type = '面试官';
                else
                    $type = '未知';
                $school = isset($reg->reginfo) ? json_decode($reg->reginfo)->personinfo->school : '未填写';
                $result->push([
                    'details' => '<a href="ot/regInfo.modal/'. $reg->id .'" data-toggle="ajaxModal" id="'. $reg->id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>',
                    'name' => $reg->user->name,
                    'school' => $reg->type == 'teamadmin' ? $reg->specific()->school->name : (isset($reg->specific()->position) ? $reg->specific()->position : '无'),
                    'committee' => isset($reg->specific()->committee) ? $reg->specific()->committee->name : '无',
                    'partner' => $type,
                    'status' => $reg->type == 'ot' ? $reg->specific()->scopeRoles() : '不适用'
                ]);
            }
            return Datatables::of($result)->make(true);
        }
    }

    /**
     * Show user datatables json
     *
     * @return string JSON of users
     */
    public function users()
    {
            $result = new Collection;
            $users = User::get(['id', 'email', 'name']);
            foreach ($users as $user)
            {
                $result->push([
                    'details' => '<a href="ot/userDetails.modal/'. $user->id .'" data-toggle="ajaxModal" id="'. $user->id .'" class="details-modal"><i class="fa fa-user-circle-o"></i></a>',
                    'reg' => '<a href="reg.modal/'. $user->id .'" data-toggle="ajaxModal" id="reg.'. $user->id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>',
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                ]);
            }

        return Datatables::of($result)->make(true);
    }

    /**
     * Show school datatables json
     *
     * @return string JSON of schools
     */
    public function schools()
    {
        $result = new Collection;
        $schools = School::get(['id', 'name']);
        $committees = Committee::all();
        foreach($schools as $school)
        {
            $delegates = $school->delegates;
            $volunteers = $school->volunteers;
            $delcount = "";
            $volcount = 'Volunteers: ' . $volunteers->whereIn('status', array('paid', 'oVerified'))->count() . '/' . $volunteers->whereIn('status', array('paid', 'oVerified', 'sVerified'))->count() . '/' . $volunteers->whereIn('status', array('paid', 'oVerified', 'sVerified', 'reg'))->count() . "<br>";
            foreach($committees as $committee)
            {
                $del = $delegates->where('committee_id', $committee->id);
                $delcount .= $committee->name . ": " . $del->whereIn('status', array('paid', 'oVerified'))->count() . '/' . $del->whereIn('status', array('paid', 'oVerified', 'sVerified'))->count() . '/' . $del->whereIn('status', array('paid', 'oVerified', 'sVerified', 'reg'))->count() . "<br>";
            }
            //TODO: observers
            $statistics = $delcount . $volcount;
            $result->push([
                'details' => '<a href="ot/schoolDetails.modal/'. $school->id .'" data-toggle="ajaxModal" id="'. $school->id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>',
                'id' => $school->id,
                'name' => $school->name,
                //'uid' => $school->user_id,
                'statistics' => $statistics,
            ]);
        }
        return Datatables::of($result)->make(true);
    }

    /**
     * Show committee datatables json
     *
     * @return string JSON of committees
     */
    public function committees()
    {
        $result = new Collection;
        $committees = Committee::where('conference_id', Reg::currentConferenceID())->get();
        foreach($committees as $committee)
        {
            $result->push([
                'details' => '<a href="ot/committeeDetails.modal/'. $committee->id .'" data-toggle="ajaxModal" id="'. $committee->id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>',
                'id' => $committee->id,
                'bt' => $committee->father_committee_id,
                'name' => $committee->name,
                'dqc' => $committee->allDelegatesQuery()->count() . ' / ' . $committee->capacity
            ]);
        }
        return Datatables::of($result)->make(true);
    }

    /**
     * Show assignment datatables json
     *
     * @return string JSON of assignments
     */
    public function assignments()
    {
        $result = new Collection;
        if (Reg::current()->type == 'dais')
            $assignments = Reg::current()->dais->committee->assignments; //Assignment::where('conference_id', Reg::currentConferenceID())->get(); 
        else
            $assignments = Reg::current()->specific()->assignments();//Assignment::all();//get(['id', 'title', 'deadline']);
        $i = 0;
        foreach($assignments as $assignment)
        {
            $title = $assignment->title;
            $detailline = '<a href="assignment/'. $assignment->id.'"><i class="fa fa-search-plus"></i></a>';
            if (Reg::current()->type == 'delegate')
            {
                if ($assignment->subject_type == 'nation')
                    $handin = Handin::where('assignment_id', $assignment->id)->where('nation_id', Reg::current()->delegate->nation->id)->first();
                else if ($assignment->subject_type == 'partner')
                {
                    if (is_object(Reg::current()->delegate->partner)) $handin = Handin::where('assignment_id', $assignment->id)->where('reg_id', Reg::current()->delegate->partner_reg_id)->orderBy('id', 'desc')->first();
                    if (!isset($handin)) $handin = Handin::where('assignment_id', $assignment->id)->where('reg_id', Reg::current()->id)->orderBy('id', 'desc')->first();
                }
                else
                    $handin = Handin::where('assignment_id', $assignment->id)->where('reg_id', Reg::current()->id)->orderBy('id', 'desc')->first();
                if (is_null($handin) || ($handin->handin_type == 'json' && !isset(json_decode($handin->content)->_token))) //TO-DO: ddl check
                    $title = $title."<b class=\"badge bg-danger pull-right\">未提交</b>";
            }
            else
            {
                $detailline = '<a href="assignment/'. $assignment->id . '/export"><i class="fa fa-folder-open"></i></a>';
                $detailline .= '&nbsp;<a href="assignmentDetails.modal/'. $assignment->id.'" data-toggle="ajaxModal"><i class="fa fa-pencil"></i></a>';
                $handin = Handin::where('assignment_id', $assignment->id)->count(); //TODO: 构建排除重复提交的查询数字
                $title = $title."<b class=\"badge bg-danger pull-right\">" . $handin . " 份提交</b>";
            }
            $result->push([
                //'id' => $assignment->id,
                'id' => ++$i, // We don't want to use the actual assignment id in the database because it may not be continuous for a delegate, and is hence not user-friendly.
                'details' => $detailline,
                'title' => $title,
                'deadline' => $assignment->deadline,
            ]);
        }
        return Datatables::of($result)->make(true);
    }

    /**
     * Show nation datatables json
     *
     * @return string JSON of nations
     */
    public function nations()
    {
        $result = new Collection;
        if (Reg::current()->type == 'ot')
        {
            $committees = Committee::where('conference_id', Reg::currentConferenceID())->get();
            $nations = Nation::whereIn('committee_id', $committees->pluck('id'))->with(['delegates', 'delegates.reg:id,user_id', 'delegates.reg.user:id,name', 'nationgroups:id,name'])->get();
            foreach($nations as $nation)
            {
                $result->push([
                    'details' => '<a href="dais/nationDetails.modal/'. $nation->id .'" data-toggle="ajaxModal" id="'. $nation->id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>',
                    'id' => $nation->id,
                    'committee' => $committees->firstWhere('id', $nation->committee_id)->name,
                    'name' => $nation->displayName(true, 0),
                    'conpetence' => $nation->conpetence,
                    'veto_power' => $nation->veto_power ? '是' : '否',
                    'nationgroup' => $nation->scopeNationGroup(true, 5),
                    'delegate' => $nation->delegateScope(),

                ]);
            }
        } else {
            return "error";
            $nations = Reg::current()->specific()->committee->nations;
            foreach($nations as $nation)
            {
                /*$groups = '';
                foreach ($nation->nationgroups as $ngroup)
                {
                    $groups = $groups . ' '. $ngroup->display_name;
                }*/

                $result->push([
                    //'details' => '<a href="ot/nationDetails.modal/'. $nation->id .'" data-toggle="ajaxModal" id="'. $nation->id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>',
                    //'id' => $nation->id,
                    //'committee' => $nation->committee->name,
                    'name' => $nation->name,
                    //'conpetence' => $nation->conpetence,
                    //'veto_power' => $nation->veto_power ? '是' : '否',
                    'nationgroup' => $nation->scopeNationGroup(true, 5),
                    'delegate' => $nation->delegateScope(true),

                ]);
            }
        }
        return Datatables::of($result)->make(true);
    }

    /**
     * Show document datatables json
     *
     * @return string JSON of documents
     */
    public function documents()
    {
        $result = new Collection;
        if (Reg::current()->type == 'dais')
            $documents = Reg::current()->dais->committee->documents;///*Reg::current()->dais->documents();*/Document::where('conference_id', Reg::currentConferenceID())->get(); 
        else
            $documents = Reg::current()->specific()->documents();//Assignment::all();//get(['id', 'title', 'deadline']);
        $i = 0;
        foreach($documents as $document)
        {
            $detailline = '<a href="document/'. $document->id.'"><i class="fa fa-search-plus"></i></a>';
            if (Reg::current()->type == 'dais')
                $detailline = $detailline . '&nbsp;<a href="documentDetails.modal/'. $document->id.'" data-toggle="ajaxModal"><i class="fa fa-pencil"></i></a>';
            $result->push([
                //'id' => $document->id,
                'details' => $detailline,
                'id' => ++$i, // We don't want to use the actual document id in the database because it may not be continuous for a delegate, and is hence not user-friendly.
                'title' => $document->title,
                'deadline' => date('Y年n月j日', strtotime($document->created_at)),
            ]);
        }
        return Datatables::of($result)->make(true);
    }

    /**
     * Show handin datatables json
     *
     * @return string JSON of handins
     */
    public function handins()
    {
        $result = new Collection;
        $handins = Handin::all();
        foreach($handins as $handin)
        {
            $groups = '';

            //To-Do

            $result->push([
                'details' => '<a href="ot/nationDetails.modal/'. $nation->id .'" data-toggle="ajaxModal" id="'. $nation->id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>',
                'id' => $nation->id,
                'committee' => $nation->committee->name,
                'name' => $nation->name,
                'conpetence' => $nation->conpetence,
                'veto_power' => $nation->veto_power ? '是' : '否',
                'nationgroup' => $groups,
            ]);
        }
        return Datatables::of($result)->make(true);
    }

    /**
     * Show good datatables json
     *
     * @return string JSON of goods
     */
    public function goods()
    {
        $result = new Collection;
        $goods = Good::whereNull('conference_id')->orWhere('conference_id', Reg::currentConferenceID())->get();//get(['id', 'title', 'deadline']);
        $i = 0;
        foreach($goods as $good)
        {
            if (!$good->enabled) continue;
            $remain = $good->remains;
            if ($remain == -1) $remain = 5;
            if ($remain > 5) $remain = 5;
            if ($remain == 0) $command = '<p class="text-muted">已售罄</p>';
            else
            {
                $options_config = json_decode($good->options, true);
                $command = '<form class="form-inline" action="'.mp_url('/store/cart/add/'.$good->id).'" method="post">';
                if (is_array($options_config))
                {
                    foreach ($options_config as $name => $option)
                    {
                        $values = $option['values'];
                        if (is_array($values))
                        {
                            $command .= '<span>' . $option['display_name'] . '： </span><select name="'.$name.'" class="form-control m-b">';
                            foreach ($values as $val_key => $val_name)
                            {
                                $command .= '<option value="'.$val_key.'">'.$val_name.'</option>';
                            }
                            $command .= '</select><br>';
                        }
                    }
                }
                $command .= '<span>数量： </span><div id="MySpinner" class="spinner input-group shop-spinner" data-min="1" data-max="'.$remain.'">'.
                      csrf_field().'
                      <input type="text" class="form-control spinner-input" value="1" name="num" maxlength="2">
                      <div class="btn-group btn-group-vertical input-group-btn">
                        <button type="button" class="btn btn-white spinner-up">
                          <i class="fa fa-chevron-up text-muted"></i>
                        </button>
                        <button type="button" class="btn btn-white spinner-down">
                          <i class="fa fa-chevron-down text-muted"></i>
                        </button>
                      </div>
                    </div>&nbsp;<button class="btn btn-success" type="submit"><i class="fa fa-plus"></i> 加入购物车</button></form>';
            }
            $result->push([
                'id' => ++$i,
                'image' => '<a href="'. mp_url('/store/good.modal/'.$good->id).'" data-toggle="ajaxModal" class="details-modal"><img src="'. mp_url('/store/goodimg/' . $good->id) . '" class="shop-image-small"></a>',
                'title' => '<a href="'. mp_url('/store/good.modal/'.$good->id).'" data-toggle="ajaxModal" class="details-modal">'.$good->name.'</a>',
                'price' => '¥' . number_format($good->price, 2),
                'command' => $command,
            ]);
        }
        return Datatables::of($result)->make(true);
    }

    /**
     * Show order datatables json
     *
     * @return string JSON of orders
     */
    public function orders($id)
    {
        $result = new Collection;
        $self = (Auth::id() == $id);
        $conf = Reg::currentConferenceID();
        if ($self || Reg::current()->can('edit-orders'))
        {
            $orders = Order::orderBy('created_at', 'desc');
            if ($conf != 0)
                $orders = $orders->where('conference_id', Reg::currentConferenceID());
            if ($id != -1)
                $orders = $orders->where('user_id', $id);
            if (!$self)
                $orders = $orders->with('user');
            $orders = $orders->get();
            $i = 0;
            foreach($orders as $order)
            {
                if ($self) {
                    $result->push([
                        'details' => '<a href="'. mp_url('/store/order/' . $order->id) .'"><i class="fa fa-search-plus"></i></a>',
                        'id' => $order->id,
                        'price' => '¥' . number_format($order->price, 2),
                        'status' => $order->statusBadge(),
                        'time' => nicetime($order->created_at),
                    ]);
                } else {
                    $result->push([
                        'details' => '<a href="'. mp_url('/store/order/' . $order->id) .'"><i class="fa fa-search-plus"></i></a>'.
                                     '<a href="'. mp_url('/store/orderAdmin.modal/' . $order->id . '?refresh=no') .'" data-toggle="ajaxModal"><i class="fa fa-id-card-o"></i></a>',
                        'id' => $order->id,
                        'uid' => $order->user_id,
                        'username' => $order->user->name,
                        'price' => '¥' . number_format($order->price, 2),
                        'status' => $order->statusBadge(),
                        'time' => nicetime($order->created_at),
                    ]);
                }
            }
        }
        return Datatables::of($result)->make(true);
    }

    /**
     * Show role datatables json (by nation)
     *
     * @return string JSON of roles
     */
    public function roleListByNation()
    {
        $result = new Collection;

        return Datatables::of($result)->make(true);
    }

    /**
     * Show role datatables json (by delegate)
     *
     * @return string JSON of roles
     */
    public function roleListByDelegate()
    {
        $result = new Collection;

        return Datatables::of($result)->make(true);
    }

    /**
     * Show role datatables json (for management)
     *
     * @return string JSON of roles
     */
    public function roleAllocNations()
    {
        $result = new Collection;
        /*
        if (Reg::current()->type != 'dais')
            $result->push([
                'select' => '*',
                'name' => '错误',
                'nationgroup' => '您没有权限',
                'delegate' => '进行该操作！',
                'command' => '<button class="btn btn-xs btn-white disabled" type="button">移出代表</button>
                              <button class="btn btn-xs btn-warning disabled" type="button">编辑</button>
                              <button class="btn btn-xs btn-danger disabled" type="button">删除</button>'
            ]);
            */
        //$mycommittee = Reg::current()->dais->committee;
        //$nations = Nation::where('committee_id', $mycommittee->id)->get();
        $nations = RoleAllocController::nations();
        $nations->load('committee');
        $nations->load('nationgroups');
        $nations->load('assignedDelegates', 'assignedDelegates.reg', 'assignedDelegates.reg.user');
        $nations->where('status', 'locked')->load('delegates', 'delegates.reg', 'delegates.reg.user');
        $autosel = false;
        foreach($nations as $nation)
        {
            $select = '<input name="nation" type="radio" value="' . $nation->id . '"';
            $delnames = '无';
            //$command = '<a href="' . mp_url('/dais/freeNation/' . $nation->id) . '" class="btn btn-xs btn-white';
            $command = '<button class="btn btn-xs btn-success freeButton" nation-id="' . $nation->id . '"type="button"';
            if ($nation->status != 'open')
            {
                $select .= ' disabled="disabled"';
                $delnames = $nation->delegateScope();
            }
            else
            {
                if ($nation->assignedDelegates->isEmpty())
                    $command .= ' disabled';
                else
                {
                    $delnames = $nation->assignedDelegateScope();
                    $command .= ' onclick="loader(this)"';
                }
                if (!$autosel)
                {
                    $select .= ' checked="true"';
                    $autosel = true;
                }
            }
            if ($nation->status == 'locked')
            {
                $select .= ' disabled="disabled"';
                $command .= ' disabled';
                $buttonText = '已经锁定';
            }
            else if ($nation->committee->maxAssignList == 1)
                $buttonText = '移出代表';
            else
                $buttonText = '清空分配';
            $select .= '>';
            $command .= '>'.$buttonText.'</button>';
            if ((Reg::current()->type == 'ot' && Reg::current()->can('edit-nations')) || Reg::current()->type == 'dais')
            {
                $command .= '<a href="dais/nationDetails.modal/'. $nation->id .'" class="btn btn-xs btn-warning details-modal" data-toggle="ajaxModal">编辑</a>'.
                            '<a href="dais/delete/nation/'. $nation->id .'" class="btn btn-xs btn-danger details-modal" data-toggle="ajaxModal">删除</a>';
            }
            switch($nation->status)
            {
                case 'selected':
                    $delnames = "<i class='fa fa-unlock-alt' aria-hidden='true'></i> ".$delnames;
                    break;
                case 'locked':
                    $delnames = "<i class='fa fa-lock' aria-hidden='true'></i> ".$delnames;
                    $delnames =  "<a style='cursor: pointer;' class='details-popover' data-html='1' data-placement='right' data-trigger='click' data-original-title='原可选此席位的代表列表' data-toggle='popover' data-content='".$nation->assignedDelegateScope()."'>".$delnames."</a>";
                    break;
                case 'open':
                    $delnames = "<i class='fa fa-unlock' aria-hidden='true'></i> ".$delnames;
            }
            $result->push([
                'select' => $select,
                'name' => $nation->displayName(),
                'committee' => $nation->committee->name,
                'nationgroup' => isset($nation->nationgroups) ? $nation->scopeNationGroup(true, 3) : '无',
                'delegate' => $delnames,
                'command' => $command
            ]);
        }
        return Datatables::of($result)->make(true);
    }

    /**
     * Show delegate datatables json (for role management)
     *
     * @return string JSON of delegates
     */
    public function roleAllocDelegates()
    {
        $result = new Collection;
        /*
        if (Reg::current()->type != 'dais')
            $result->push([
                'name' => '错误',
                'school' => '您没有权限',
                'nation' => '进行该操作！',
                'command' => '<button class="btn btn-xs btn-success addButton" del-id="' . $delegate->user->id . '"type="button">移入席位</button>'
            ]);
            */
        //$mycommittee = Reg::current()->dais->committee;
        /*$delegates = Delegate::where(function($query) {
            $query->where('committee_id', Reg::current()->dais->committee->id)
            ->where('status', 'paid');
        })->orWhere(function($query) {
            $query->where('committee_id', Reg::current()->dais->committee->id)
            ->where('status', 'oVerified');
        })->get(['reg_id', 'school_id', 'nation_id', 'committee_id', 'status']);*/
        $delegates = RoleAllocController::delegates();
        $delegates->load('delegategroups', 'committee', 'reg', 'reg.user', 'nation', 'interviews', 'conference');
        $delegates->where('seat_locked', false)->load('assignedNations');
        foreach($delegates as $delegate)
        {
            if (!$delegate->canAssignSeats())
                continue;
            $name = $delegate->reg->user->name;
            $name .= ' ('.($delegate->delegategroups->count() > 0 ? $delegate->delegateGroupScope(true, 0, true) . ', ' : '').$delegate->statusText().',搭档'.(is_object($delegate->partner)?$delegate->partner->reg->user->name : '无').')';
            if ($delegate->seat_locked)
                $command = '已锁定';
            else {
                switch ($delegate->committee->maxAssignList)
                {
                    case 0:
                        $command = '该委员会未开启席位分配';
                        break;
                    case 1:
                        $command = isset($delegate->nation) ? '<a href="'.mp_url('/dais/removeSeat/'.$delegate->reg->id).'" class="btn btn-xs btn-white" type="button">移出席位</a>'
                                                            : '<button class="btn btn-xs btn-success addButton" del-id="' . $delegate->reg->id . '"type="button" onclick="loader(this)">移入席位</button>';
                        break;
                    default:
                        $command = '';
                        if ($delegate->assignedNations->count() < $delegate->committee->maxAssignList || $delegate->committee->maxAssignList == -1)
                            $command = '<button class="btn btn-xs btn-success addButton" del-id="' . $delegate->reg->id . '" type="button" onclick="loader(this)">添加席位</button>';
                        $command .= '<a href="'.mp_url('/ot/regInfo.modal/'.$delegate->reg_id.'?active=seats').'" data-toggle="ajaxModal" class="btn btn-xs btn-white details-modal">编辑列表</a>';
                        $command .= '<a href="'.mp_url('/dais/seatSMS.modal/'.$delegate->reg_id).'" data-toggle="ajaxModal" class="btn btn-xs btn-info details-modal">短信通知</a>';
                }
            }
            $result->push([
                'uid' => '<a href="'.mp_url('ot/regInfo.modal/'. $delegate->reg_id) .'" data-toggle="ajaxModal">'.$delegate->reg_id.'</a>',
                'name' => '<a href="'.mp_url('ot/regInfo.modal/'. $delegate->reg_id) .'" data-toggle="ajaxModal">'.$name.'</a>',
                'committee' => $delegate->committee->name,
                'nation' => $delegate->nationName(true),//isset($delegate->nation) ? $delegate->nation->name : '待分配',
                'command' => $command
            ]);
        }
        return Datatables::of($result)->make(true);
    }
}
