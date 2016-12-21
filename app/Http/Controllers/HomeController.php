<?php

namespace App\Http\Controllers;

use App\Committee;
use App\School;
use App\Delegate;
use App\Volunteer;
use App\Observer;
use App\User;
use App\Assignment;
use App\Handin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Config;

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
        else if ($type == 'dais')
        {
            return "Aloha Dais";
        }
        else
        {
            $specific = Auth::user()->specific();
            $changable = Config::get('munpanel.registration_enabled');
            if (is_null($specific))
            {
                $percent = 0;
                $status = '未报名';
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
        $changable = Config::get('munpanel.registration_enabled') || (Auth::user()->type == 'ot');
        $specific = $user->specific();
        if ((!is_null($specific)) && Auth::user()->type != 'ot')
            if ($specific->status == 'oVerified' || $specific->status == 'paid')
                $changable = false;
        if (Auth::user()->type == 'school' && Config::get('munpanel.registration_school_changable'))
            $changable = true;
        return view('regModal', ['committees' => Committee::all(), 'schools' => School::all(), 'id' => $id, 'user' => $user, 'delegate' => $user->delegate, 'volunteer' => $user->volunteer, 'observer' => $user->observer, 'changable' => $changable]);
    }

    public function regManage()
    {
        $type =Auth::user()->type;
        if ($type == 'ot' && Auth::user()->can('edit-users'))
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
        return view('invoice', ['invoiceItems' => Auth::user()->invoiceItems(), 'invoiceAmount' => Auth::user()->invoiceAmount()]);
    }

    public function checkout()
    {
        return view('checkoutModal');
    }
    
    public function assignmentsList()
    {
        if (Auth::user()->type != 'delegate')
            return view('error', ['msg' => '您不是参会代表，无权访问该页面！']);
        if (Auth::user()->specific()->status != 'paid')
            return view('error', ['msg' => '请先缴清会费！']);
        $committee = Auth::user()->specific()->committee;
        return view('assignmentsList', ['committee' => $committee]);
    }

    public function assignment($id)
    {
        //TO-DO: Verify if it's the assignment of the user.
        $assignment = Assignment::findOrFail($id);
        if ($assignment->subject_type == 'nation')
            $handin = Handin::where('assignment_id', $id)->where('nation_id', Auth::user()->delegate->nation->id)->orderBy('id', 'desc')->first();
        else
            $handin = Handin::where('assignment_id', $id)->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
        if (!is_null($handin))
        {
            return view('assignmentHandinInfo', ['assignment' => $assignment, 'handin' => $handin]);
        }
        else if ($assignment->handin_type == 'upload')
        {
            return view('assignmentHandinUpload', ['assignment' => $assignment]);
        }
        else
        {
            //TO-DO Text Mode Hand in
            return "Under Development...";
        }
    }

    public function uploadAssignment(Request $request, $id)
    {
        //TO-DO: Verify if it's the assignment of the user.
        if ($request->hasFile('file') && $request->file('file')->isValid())
        {
            $handin = new Handin;
            $assignment = Assignment::findOrFail($id);
            if ($assignment->subject_type == 'nation')
                $handin->nation_id = Auth::user()->delegate->nation->id;
            //else
            $handin->user_id = Auth::user()->id;
            $handin->content = $request->file->store('assignmentHandins');
            $handin->assignment_id = $id;
            $handin->handin_type = 'upload';
            $handin->remark = $request->remark;
            $handin->save();
            return redirect(secure_url('/assignment/' . $id));
        }
        else
        {
            return "Error";
        }
    }

    public function downloadAssignment($id)
    {
        $assignment = Assignment::findOrFail($id);
        if ($assignment->subject_type == 'nation')
            $handin = Handin::where('assignment_id', $id)->where('nation_id', Auth::user()->delegate->nation->id)->orderBy('id', 'desc')->first();
        else
            $handin = Handin::where('assignment_id', $id)->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
        if (is_null($handin))
            return "ERROR";
        return response()->download(storage_path('/app/'.$handin->content));
    }
}
