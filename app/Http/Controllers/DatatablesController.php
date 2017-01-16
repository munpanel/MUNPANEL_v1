<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Yajra\Datatables\Datatables;
use App\User;
use App\Delegate;
use App\Volunteer;
use App\School;
use App\Committee;
use App\Assignment;
use App\Handin;
use App\Nation;
use App\Document;
use Config;
use Illuminate\Support\Facades\Auth;

class DatatablesController extends Controller //To-Do: Permission Check
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function registrations()
    {
        $user = Auth::user();
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
            if (!Auth::user()->can('view-regs'))
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
                    'details' => '<a href="reg.modal/'. $delegate->user_id .'" data-toggle="ajaxModal" id="'. $delegate->user_id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>',
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

    public function users()
    {
            $result = new Collection;
            $users = User::get(['id', 'email', 'name', 'type']);
            foreach ($users as $user)
            {
                if ($user->type == 'unregistered')
                    $type = '未报名';
                else if ($user->type == 'ot')
                    $type = '组织团队';
                else if ($user->type == 'dais')
                    $type = '学术团队';
                else if ($user->type == 'delegate')
                    $type = '代表';
                else if ($user->type == 'volunteer')
                    $type = '志愿者';
                else if ($user->type == 'observer')
                    $type = '观察员';
                else if ($user->type == 'school')
                    $type = '学校';
                else
                    $type = '未知';
                $result->push([
                    'details' => '<a href="ot/userDetails.modal/'. $user->id .'" data-toggle="ajaxModal" id="'. $user->id .'" class="details-modal"><i class="fa fa-user-circle-o"></i></a>',
                    'reg' => '<a href="reg.modal/'. $user->id .'" data-toggle="ajaxModal" id="reg.'. $user->id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>',
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'type' =>$type,
                ]);
            }
 
        return Datatables::of($result)->make(true);
    }

    public function schools()
    {
        $result = new Collection;
        $schools = School::get(['id', 'name', 'user_id']);
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
                'uid' => $school->user_id,
                'statistics' => $statistics,
            ]);
        }
        return Datatables::of($result)->make(true);
    }

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
    
    public function assignments()
    {
        $result = new Collection;
        if (Auth::user()->type == 'dais')
            $assignments = /*Auth::user()->dais->Assignment();*/Assignment::all(); // TODO: get docs per committee 
        else 
            $assignments = Auth::user()->delegate->assignments();//Assignment::all();//get(['id', 'title', 'deadline']);
        $i = 0;
        foreach($assignments as $assignment)
        {
            $title = $assignment->title;
            $detailline = '<a href="assignment/'. $assignment->id.'"><i class="fa fa-search-plus"></i></a>';
            if (Auth::user()->type == 'delegate')
            {
                if ($assignment->subject_type == 'nation')
                    $handin = Handin::where('assignment_id', $assignment->id)->where('nation_id', Auth::user()->delegate->nation->id)->first();
                else if ($assignment->subject_type == 'partner')
                    $handin = Handin::where('assignment_id', $id)->where('nation_id', Auth::user()->delegate->partner->user->id)->orderBy('id', 'desc')->first();
                if (!isset($handin)) $handin = Handin::where('assignment_id', $id)->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();                
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

    public function nations()
    {
        $result = new Collection;
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
            ]);
        }
        return Datatables::of($result)->make(true);
    }
    
    public function documents()
    {
        $result = new Collection;
        if (Auth::user()->type == 'dais')
            $documents = /*Auth::user()->dais->documents();*/Document::all(); // TODO: get docs per committee
        else
            $documents = Auth::user()->delegate->documents();//Assignment::all();//get(['id', 'title', 'deadline']);
        $i = 0;
        foreach($documents as $document)
        {
            $detailline = '<a href="document/'. $document->id.'"><i class="fa fa-search-plus"></i></a>';
            if (Auth::user()->type == 'dais')
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
}
