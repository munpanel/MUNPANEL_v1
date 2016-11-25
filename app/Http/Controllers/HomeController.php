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
            return view('ot.home');
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
            if (is_null($specific))
            {
                $percent = 0;
                $status = '未注册';
            }
            else if ($specific->status == 'reg')
            {
                $percent = 33;
                $status = '等待学校审核';
            }
            else if ($specific->status == 'sVerified')
            {
                $percent = 67;
                $status = '等待组织团队审核';
            }
            else
            {
                $percent = 100;
                $status = '注册成功';
            }
            return view('home', ['percent' => $percent, 'status' => $status]);
        }
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
}
