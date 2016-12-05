<?php

namespace App\Http\Controllers;

use App\Committee;
use App\School;
use App\Delegate;
use App\Volunteer;
use App\Observer;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $activep['home'] = true;
        $type =Auth::user()->type;
        if ($type == 'ot')
        {
            return view('ot.home', ['committees' => Committee::all(), 'vol' =>Volunteer::count()]);
        }
        else if ($type == 'school')
        {
            $school = Auth::user()->school;
            //return $del->count();
            return view('school.home', ['del' => $school->delegates->count(), 'vol' => $school->volunteers->count()]);
        }
        else
        {
            $specific = Auth::user()->specific();
            $changable = true;
            if (is_null($specific))
            {
                $percent = 0;
                $status = '未注册';
                if ($type == 'delegate') //Deal with YCZ-ECO Situation
                {
                    $status = '等待重填';
                    $notice_msg = '抱歉，您之前报名了中文ECOSOC委员会，现因组织团队疏忽，需要您重新填写报名表单之后社团重新审核。感谢您的理解与支持。';
                }
            }
            else if ($specific->status == 'reg')
            {
                $percent = 25;
                $status = '等待学校审核';
            }
            else if ($specific->status == 'sVerified')
            {
                $percent = 50;
                $status = '等待组织团队审核';
            }
            else  if ($specific->status == 'oVerified')
            {
                $percent = 75;
                $status = '待缴费';
                $changable = false;
            }
            else
            {
                $percent = 100;
                $status = "已缴费";
                $changable = false;
            }
            if (isset($notice_msg))
                return view('home', ['percent' => $percent, 'status' => $status, 'changable' => $changable, 'notice_msg' => $notice_msg]);
            else
                return view('home', ['percent' => $percent, 'status' => $status, 'changable' => $changable]);
        }
    }

    public function changePwd()
    {
        return view('changePwdModal');
    }

    public function regModal($id = null)
    {
        $user = User::find($id);
        if (is_null($id))
            $user = Auth::user();
        else if (Auth::user()->type != 'ot' && Auth::user()->type != 'school')
            return "error";
        else if (Auth::user()->type == 'school' && Auth::user()->school->id != $user->specific()->school->id)
            return "error";
        return view('regModal', ['committees' => Committee::all(), 'schools' => School::all(), 'id' => $id, 'user' => $user, 'delegate' => $user->delegate, 'volunteer' => $user->volunteer, 'observer' => $user->observer]);
    }

    public function regManage()
    {
        $type =Auth::user()->type;
        if ($type == 'ot')
        {
            return view('ot.regManage', ['delegates' => Delegate::all(), 'volunteers' => Volunteer::all(), 'observers' => Observer::all()]);
        }
        else if ($type == 'school')
        {
            $school = Auth::user()->school;
            //return response()->json($school);
            //$delegates = $school->delegates;
            //return response()->json($delegates);//;delegates);
            return view('school.regManage', ['delegates' => $school->delegates, 'volunteers' => $school->volunteers, 'observers' => $school->observers]);
        }
        else
        {
            return "Illegal Request";
        }
    }

    public function userManage()
    {
        if ( Auth::user()->type != 'ot' )
            return 'Error';
        return view('ot.userManage');
    }

    public function schoolManage()
    {
        if ( Auth::user()->type != 'ot' )
            return 'Error';
        return view('ot.schoolManage');
    }

    public function committeeManage()
    {
        if ( Auth::user()->type != 'ot' )
            return 'Error';
        return view('ot.committeeManage');
    }

    public function userDetailsModal($id)
    {
        if (Auth::user()->type != 'ot')
            return "Error";
        if ($id == 'new')
        {
            $user = new User;
            $user->email = 'newuser@munpanel.com';
            $user->password = 'noLogin';
            $user->name = 'New User';
            $user->type = 'unregistered';
            $user->save();
        }
        else
            $user = User::findOrFail($id);
        return view('ot.userDetailsModal', ['user' => $user]);
    }

    public function schoolDetailsModal($id)
    {
        if (Auth::user()->type != 'ot')
            return "Error";
        if ($id == 'new')
        {
            $school = new School;
            $school->name = 'New School'; //TO-DO: default status disabled
            $school->user_id = 1;
            $school->save();
        }
        else
            $school = School::findOrFail($id);
        return view('ot.schoolDetailsModal', ['school' => $school]);
    }

    public function committeeDetailsModal($id)
    {
        if (Auth::user()->type != 'ot')
            return "Error";
        if ($id == 'new')
        {
            $committee = new Committee;
            $committee->name = 'New Committee';
            $committee->save();
        }
        else
            $committee = Committee::findOrFail($id);
        return view('ot.committeeDetailsModal', ['committee' => $committee]);
    }

    public function invoice()
    {
        if (Auth::user()->type == 'unregistered')
            return view('error', ['msg' => 'Please register first.']);
        if (Auth::user()->specific()->status != 'oVerified' && Auth::user()->specific()->status != 'paid')
            return view('error', ['msg' =>'You have to be verified by the Organizing Team first.']);
        return view('invoice');
    }

    public function checkout()
    {
        return view('checkoutModal');
    }
}
