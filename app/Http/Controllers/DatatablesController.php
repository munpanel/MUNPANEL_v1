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

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Yajra\Datatables\Datatables;
use App\User;
use App\Reg;
use App\Delegate;
use App\Volunteer;
use App\School;
use App\Committee;
use App\Assignment;
use App\Handin;
use App\Nation;
use App\Good;
use App\Document;
use Config;
use Illuminate\Support\Facades\Auth;

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
     * Show registration datatables json
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
        $conf = 2;
        if ($user->type=='ot')
        {
            if (false)//(!Reg::current()->can('view-regs'))
                return "ERROR";
            $result = new Collection;
            // 过滤结果: 只保留 delegate, observer 和 volunteer
            $regs = Reg::where('conference_id', 2)->whereIn('type', ['delegate','volunteer','observer'])->with(['user' => function($q) {$q->select('name', 'id');}])->get(['id', 'user_id', 'type']);
            foreach ($regs as $reg)
            {
                if ($reg->type == 'unregistered')
                    $type = '未报名';
                else if ($reg->type == 'ot')
                    $type = '组织团队';
                else if ($reg->type == 'dais')
                    $type = '学术团队';
                else if ($reg->type == 'delegate')
                    $type = '代表';
                else if ($reg->type == 'volunteer')
                    $type = '志愿者';
                else if ($reg->type == 'observer')
                    $type = '观察员';
                else if ($reg->type == 'school')
                    $type = '学校';
                else
                    $type = '未知';
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
                 if ($type == '代表')
                     $status = $reg->specific()->statusText();
            }
            else $status = '';
                $school = isset($reg->reginfo) ? json_decode($reg->reginfo)->personinfo->school : '未填写';
                $result->push([
                    'details' => '<a href="ot/regInfo.modal/'. $reg->id .'" data-toggle="ajaxModal" id="'. $reg->id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>',
                    'name' => $reg->user->name,
                    'school' => $school,
                    'committee' => isset($reg->specific()->committee) ? $reg->specific()->committee->name : '无',
                    'partner' => $type,
                    'status' => $status,
                ]);
            }
        }
        return Datatables::of($result)->make(true);
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
        $committees = Committee::get(['id', 'name']);
        foreach($committees as $committee)
        {
            $result->push([
                'details' => '<a href="ot/committeeDetails.modal/'. $committee->id .'" data-toggle="ajaxModal" id="'. $committee->id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>',
                'id' => $committee->id,
                'name' => $committee->name,
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
            $assignments = /*Reg::current()->dais->Assignment();*/Assignment::all(); // TODO: get docs per committee 
        else 
            $assignments = Reg::current()->delegate->assignments();//Assignment::all();//get(['id', 'title', 'deadline']);
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
                    if (isset(Reg::current()->delegate->partner)) $handin = Handin::where('assignment_id', $assignment->id)->where('user_id', Reg::current()->delegate->partner->id)->orderBy('id', 'desc')->first();
                    if (!isset($handin)) $handin = Handin::where('assignment_id', $assignment->id)->where('reg_id', Reg::current()->id)->orderBy('id', 'desc')->first();                
                }
                else
                    $handin = Handin::where('assignment_id', $assignment->id)->where('reg_id', Reg::current()->id)->orderBy('id', 'desc')->first();                
                if (is_null($handin)) //TO-DO: ddl check
                    $title = $title."<b class=\"badge bg-danger pull-right\">未提交</b>";
            }
            else
            {
                $detailline = '<a href="assignment/'. $assignment->id . '/handins"><i class="fa fa-folder-open"></i></a>';
                $detailline .= '&nbsp;<a href="assignmentDetails.modal/'. $assignment->id.'"><i class="fa fa-pencil"></i></a>';
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
            $nations = Nation::all();
            foreach($nations as $nation)
            {
                $groups = '';
                foreach ($nation->nationgroups as $ngroup)
                {
                    $groups = $groups . ' '. $ngroup->display_name;
                }

                $result->push([
                    'details' => '<a href="ot/nationDetails.modal/'. $nation->id .'" data-toggle="ajaxModal" id="'. $nation->id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>',
                    'id' => $nation->id,
                    'committee' => $nation->committee->name,
                    'name' => $nation->name,
                    'conpetence' => $nation->conpetence,
                    'veto_power' => $nation->veto_power ? '是' : '否',
                    'nationgroup' => $groups,
                    'delegate' => $nation->scopeDelegate(),

                ]);
            }
        } else {
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
                    'nationgroup' => $nation->scopeNationGroup(),
                    'delegate' => $nation->scopeDelegate(true),

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
            $documents = /*Reg::current()->dais->documents();*/Document::all(); // TODO: get docs per committee
        else
            $documents = Reg::current()->delegate->documents();//Assignment::all();//get(['id', 'title', 'deadline']);
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
        $goods = Good::all();//get(['id', 'title', 'deadline']);
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
                $command = '<form class="form-inline" action="'.mp_url('/store/cart/add/'.$good->id).'" method="post">
                      <span>数量： </span><div id="MySpinner" class="spinner input-group shop-spinner" data-min="1" data-max="'.$remain.'">'.
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
        $mycommittee = Reg::current()->dais->committee;
        $nations = Nation::where('committee_id', $mycommittee->id)->get();
        $autosel = false;
        foreach($nations as $nation)
        {
            $select = '<input name="nation" type="radio" value="' . $nation->id . '"';
            $delnames = '无';
            $command = '<a href="' . mp_url('/dais/freeNation/' . $nation->id) . '" class="btn btn-xs btn-white';
            if (!$nation->delegates->isEmpty())
            {
                $select .= ' disabled="disabled"';
                $delnames = $nation->scopeDelegate();
            }            
            else
            {
                $command .= ' disabled';
                if (!$autosel)
                {
                    $select .= ' checked="true"';
                    $autosel = true;
                }
            }
            $select .= '>';
            $command .= '">移出代表</a>
                        <a href="dais/nationDetails.modal/'. $nation->id .'" class="btn btn-xs btn-warning details-modal">编辑</a>
                        <a href="dais/delete/nation/'. $nation->id .'" class="btn btn-xs btn-danger details-modal">删除</a>';
                        // To-Do: make all those HTTP requests of the buttons JS-based
            $result->push([
                'select' => $select,
                'name' => $nation->name,
                'nationgroup' => isset($nation->nationgroups) ? $nation->scopeNationGroup() : '无',
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
        if (Reg::current()->type != 'dais')
            $result->push([
                'name' => '错误',
                'school' => '您没有权限',
                'nation' => '进行该操作！',
                'command' => '<button class="btn btn-xs btn-success addButton" del-id="' . $delegate->user->id . '"type="button">移入席位</button>'
            ]);
        $mycommittee = Reg::current()->dais->committee;
        $delegates = Delegate::where(function($query) {
            $query->where('committee_id', Reg::current()->dais->committee->id)
            ->where('status', 'paid');
        })->orWhere(function($query) {
            $query->where('committee_id', Reg::current()->dais->committee->id)
            ->where('status', 'oVerified');
        })->get(['user_id', 'school_id', 'nation_id', 'status']);
        foreach($delegates as $delegate)
        {
            $name = $delegate->user->name;
            $surfix = $delegate->scopeDelegateGroup();
            if ($surfix != '')
                $name .= ' (' . $surfix . ')';
            if ($delegate->status != 'paid')
                $name .= '（未缴费）';
            $result->push([
                'uid' => $delegate->user_id,
                'name' => $name,
                'school' => $delegate->school->name,
                'nation' => isset($delegate->nation) ? $delegate->nation->name : '待分配',
                'command' => isset($delegate->nation) ? '<a href="'.mp_url('/dais/removeSeat/'.$delegate->user->id).'" class="btn btn-xs btn-white" type="button">移出席位</a>'
                                                      : '<button class="btn btn-xs btn-success addButton" del-id="' . $delegate->user->id . '"type="button">移入席位</button>'
            ]);
        }
        return Datatables::of($result)->make(true);
    }
}
