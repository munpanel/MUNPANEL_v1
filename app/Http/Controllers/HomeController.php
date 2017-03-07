<?php

namespace App\Http\Controllers;

use App\Conference;
use App\Committee;
use App\School;
use App\Delegate;
use App\Volunteer;
use App\Observer;
use App\User;
use App\Assignment;
use App\Handin;
use App\Document;
use App\Email;
use App\Reg;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Config;
use Zipper;
use File;
use ZipArchive;

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
        $reg = Reg::findOrFail(session('reg_id'));
        $type = $reg->type;
        if ($type == 'ot')
        {
            return view('ot.home', ['committees' => Committee::all(), 'vol' =>Volunteer::count()]);
        }
        else if ($type == 'school')
        {
            $school = $reg->school;
            //return $del->count();
            return view('school.home', ['del' => $school->delegates->count(), 'vol' => $school->volunteers->count()]);
        }
        else if ($type == 'dais')
        {
            return view('dais.home');
        }
        else
        {
            $specific = $reg->specific();
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

    /**
     * Show the password changing modal.
     *
     * @return \Illuminate\Http\Response
     */
    public function changePwd()
    {
        return view('changePwdModal');
    }

    /**
     * (Deprecated) Show the registration form modal.
     * Static form, used in BJMUNC 2017.
     *
     * @param int $id UID of the user
     * @return \Illuminate\Http\Response
     */
    public function regModal($id = null)
    {
        $user = User::find($id);
        if (is_null($id))
            $user = Auth::user();
        else if (Reg::current()->type != 'ot' && Reg::current()->type != 'school')
            return "error";
        else if (Reg::current()->type == 'school' && Reg::current()->school->id != $user->specific()->school->id)
            return "error";
        $schools = array();
        if (Reg::current()->type == 'ot')
        {
            $schools = School::all();
            foreach ($schools as $school)
            {
                if ($school->user_id == 1)
                    $school->name .= '(非成员校)';
            }
        }
        else
            $schools = School::where('user_id', '!=', 1)->get(); // Member Schools only
        $changable = Config::get('munpanel.registration_enabled') || (Reg::current()->type == 'ot');
        $specific = $user->specific();
        if ((isset($specific)) && Reg::current()->type != 'ot')
            if ($specific->status == 'oVerified' || $specific->status == 'paid' || $specific->school->user_id == 1)
                $changable = false;
        if (Reg::current()->type == 'school' && Config::get('munpanel.registration_school_changable'))
            $changable = true;
        return view('regModal', ['committees' => Committee::all(), 'schools' => $schools, 'id' => $id, 'user' => $user, 'delegate' => $user->delegate, 'volunteer' => $user->volunteer, 'observer' => $user->observer, 'changable' => $changable]);
    }

    /**
     * Show the registration form modal.
     * Dynamic form, used in ROMUNC 2017.
     *
     * @return \Illuminate\Http\Response
     */ 
    public function reg2Modal($regType)
    {
        $customTable = json_decode(Conference::findOrFail(2)->tableSettings)->regTable;
        $confForm = FormController::render($customTable->conference->items, $regType, 'uses');
        return view('reg2Modal', ['regType' => $regType, 'customTable' => $customTable, 'confForm' => $confForm]);
    }

    /**
     * Show the registration management page.
     *
     * @return \Illuminate\Http\Response
     */
    public function regManage()
    {
        $type = Reg::current()->type;
        if ($type == 'ot' && Reg::current()->can('edit-users'))
        {
            return view('ot.regManage', ['delegates' => Delegate::all(), 'volunteers' => Volunteer::all(), 'observers' => Observer::all()]);
        }
        else if ($type == 'school')
        {
            $school = Reg::current()->school;
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

    /**
     * Show the user management page.
     *
     * @return \Illuminate\Http\Response
     */
    public function userManage()
    {
        if ( Reg::current()->type != 'ot' )
            return 'Error';
        return view('ot.userManage');
    }

    /**
     * Show the school management page.
     *
     * @return \Illuminate\Http\Response
     */
    public function schoolManage()
    {
        if ( Reg::current()->type != 'ot' )
            return 'Error';
        return view('ot.schoolManage');
    }

    /**
     * Show the committee management page.
     *
     * @return \Illuminate\Http\Response
     */
    public function committeeManage()
    {
        if ( Reg::current()->type != 'ot' )
            return 'Error';
        return view('ot.committeeManage');
    }

    /**
     * Show the nation management page.
     *
     * @return \Illuminate\Http\Response
     */
    public function nationManage()
    {
        if ( Reg::current()->type != 'ot' )
            return 'Error';
        return view('ot.nationManage');
    }

    /**
     * Show the user details modal.
     *
     * @param int $id UID of user queried
     * @return \Illuminate\Http\Response
     */
    public function userDetailsModal($id)
    {
        if (Reg::current()->type != 'ot')
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

    /**
     * Show the user registration info modal.
     *
     * @param int $id UID of user queried
     * @return \Illuminate\Http\Response
     */
    public function regInfoModal($id)
    {
        if (Reg::current()->type != 'ot')
            return "Error";
        $reg = Reg::findOrFail($id);
        $allRegs = Reg::where('user_id', $reg->user_id)->get(['id', 'type']);
        return view('ot.regInfoModal', ['reg' => $reg, 'allRegs' => $allRegs]);
    }

    /**
     * Show the school details modal.
     *
     * @param int $id ID of school queried
     * @return \Illuminate\Http\Response
     */
    public function schoolDetailsModal($id)
    {
        if (Reg::current()->type != 'ot')
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

    /**
     * Show the committee details modal.
     *
     * @param int $id ID of committee queried
     * @return \Illuminate\Http\Response
     */
    public function committeeDetailsModal($id)
    {
        if (Reg::current()->type != 'ot')
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

    /**
     * (Deprecated) Show the invoice of the user for registration. 
     * This function will be replaced by the more general Order class
     * in the future.
     *
     * @return \Illuminate\Http\Response
     */
    public function invoice()
    {
        if (Reg::current()->type == 'unregistered')
            return view('error', ['msg' => 'Please register first.']);
        if (Reg::current()->specific()->status != 'oVerified' && Reg::current()->specific()->status != 'paid')
            return view('error', ['msg' =>'You have to be verified by the Organizing Team first.']);
        if (Reg::current()->specific()->school->payment_method == 'group' && Reg::current()->specific()->status != 'paid')
            return view('error', ['msg' => '贵校目前配置为统一缴费，请联系社团管理层缴费。我们亦提供直接每人线上使用微信支付、支付宝线上支付自动确认的便捷服务，如需使用请联系社团管理层在学校后台修改支付方式。']);
        return view('invoice', ['invoiceItems' => Auth::user()->invoiceItems(), 'invoiceAmount' => Auth::user()->invoiceAmount()]);
    }

    /**
     * Show the school payment management page.
     *
     * @return \Illuminate\Http\Response
     */
    public function schoolPay()
    {
        if (Reg::current()->type != 'school')
            return view('error', ['msg' => 'You have to use your school account!']);
        return view('school.pay');
    }
    
    /**
     * Change the payment method of a school
     *
     * @param string $method 'individual' or 'pork'
     * @return \Illuminate\Http\Response
     */
    public function changeSchoolPaymentMethod($method)
    {
        Reg::current()->school->payment_method = $method;
        Reg::current()->school->save();
        return redirect(secure_url('/school/payment'));
    }

    /**
     * Show the purchase modal in which users pay for
     * invoices using WeChat or Alipay through Teegon API.
     *
     * @param string $method 'individual' or 'pork'
     * @return \Illuminate\Http\Response
     */
    public function checkout($id)
    {
        return view('checkoutModal', ['id' => $id]);
    }
    
    /**
     * Show the assignment list page of user.
     *
     * @return \Illuminate\Http\Response
     */
    public function assignmentsList()
    {
        if (Reg::current()->type == 'unregistered')
            return view('error', ['msg' => '您无权访问该页面！']);
        if (Reg::current()->type == 'delegate')
        {
            if (Reg::current()->specific()->status == 'reg')//TO-DO: parameters for this
                return view('error', ['msg' => '请等待学校和/或组织团队审核！']);  
            if (Reg::current()->specific()->status != 'paid')//TO-DO: parameters for this  
                return view('error', ['msg' => '请先缴费！如果您已通过社团缴费，请等待组织团队确认']); 
        }
        $committee = Reg::current()->specific()->committee;
        return view('assignmentsList', ['committee' => $committee, 'type' => Reg::current()->type]);
    }

    /**
     * Perform actions related to one assignment.
     *
     * @param int $id the ID of the assignment
     * @param string $action the action on the assignment to be performed
     * @return \Illuminate\Http\Response
     */
    public function assignment($id, $action = 'info')
    {
        $assignment = Assignment::findOrFail($id);
        if (Reg::current()->type != 'ot' && Reg::current()->type != 'dais' && (!$assignment->belongsToDelegate(Reg::current()->id)))
            return "ERROR"; //TO-DO: Permission check for ot and dais (for downloading handins)
        if (Reg::current()->type == 'ot') //To-Do: Dais
            $handins = $assignment->handins;
        else if ($assignment->subject_type == 'nation')
            $handin = Handin::where('assignment_id', $id)->where('nation_id', Reg::current()->delegate->nation->id)->orderBy('id', 'desc')->first();
        else if ($assignment->subject_type == 'partner')
        {
            if (isset(Reg::current()->delegate->partner)) $handin = Handin::where('assignment_id', $id)->where('user_id', Reg::current()->delegate->partner->id)->orderBy('id', 'desc')->first();
            if (!isset($handin)) $handin = Handin::where('assignment_id', $id)->where('user_id', Reg::current()->id)->orderBy('id', 'desc')->first();
            else
            {
                $handin1 = Handin::where('assignment_id', $id)->where('user_id', Reg::current()->id)->orderBy('id', 'desc')->first();
                if (isset($handin1) && $handin1->id > $handin->id) $handin = $handin1;
            }
        }
        else
            $handin = Handin::where('assignment_id', $id)->where('user_id', Reg::current()->id)->orderBy('id', 'desc')->first();
        if ($action == 'info')
        {
            if (isset($handin))
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
        else if ($action == "download")
        {
            if (is_null($handin))
                return "ERROR";
            return response()->download(storage_path('/app/'.$handin->content));
        }
        else if ($action == "resubmit")
        {
            if (is_null($handin))
                return redirect(secure_url('/assignment/' . $id));
            if (strtotime(date("y-m-d h:i:s")) < strtotime($assignment->deadline))
            {
                if ($assignment->handin_type == 'upload')
                {
                    return view('assignmentHandinUpload', ['assignment' => $assignment]);
                }
                else
                {
                    //TO-DO Text Mode Hand in
                    return "Under Development...";
                }
            }
            return "ERROR";
        }
        else if ($action == "export")
        {
            $assignment = Assignment::findOrFail($id);
            $zipname = $assignment->title . ' ' . date("y-m-d-H-i-s") . '.zip';
            $zippername = '../storage/app/assignmentExports/' . $zipname;
            $zip = new ZipArchive();
            $zip->open($zippername, ZipArchive::CREATE);
            // There seems to be bugs with Laravel::Zipper, so we use the ZipArchive of PHP.
            // Since we have updated Zipper to newer version, testing is required. By Adam Yi Mar 6th 2017
            $i = 0;
            foreach($handins as $handin)
            {
                $filename = $handin->user->id . '_' . $handin->user->name . ' ' . date('y-m-d-H-i-s', strtotime($handin->updated_at)) . '.' . File::extension(storage_path('/app/'.$handin->content));
                $zip->addFile(storage_path('app/' . $handin->content), $filename);
                //Zipper::zip($zippername)->addString($filename, Storage::get($handin->content));
            }
            $zip->close();
            return response()->download(storage_path('app/assignmentExports/'. $zipname));
        }
    }

    /**
     * Upload a new handin of an assignment.
     *
     * @param Request $request
     * @param int $id the ID of the assignment
     * @return \Illuminate\Http\Response
     */
    public function uploadAssignment(Request $request, $id)
    {
        $assignment = Assignment::findOrFail($id);
        if (!$assignment->belongsToDelegate(Reg::current()->id))
            return "ERROR";
        if (strtotime(date("y-m-d H:i:s")) >= strtotime($assignment->deadline))
            return "ERROR";
        if ($request->hasFile('file') && $request->file('file')->isValid())
        {
            $handin = new Handin;
            if ($assignment->subject_type == 'nation')
                $handin->nation_id = Reg::current()->delegate->nation->id;
            //else
            $handin->user_id = Reg::current()->id;
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
    

    /**
     * Show a modal in which admins can choose to import
     * or export registration information.
     *
     * @return \Illuminate\Http\Response
     */
    public function imexportRegistrations()
    {
        return view('ot.imexportModal', ['importURL' => secure_url('/regManage/import'), 'exportURL' => secure_url('/regManage/export')]);
    }


    /**
     * Show the document list page.
     *
     * @return \Illuminate\Http\Response
     */
    public function documentsList()
    {
        if (Reg::current()->type == 'unregistered')
            return view('error', ['msg' => '您无权访问该页面！']);
        if (Reg::current()->type == 'delegate')
        {
            if (Reg::current()->specific()->status == 'reg')//TO-DO: parameters for this
                return view('error', ['msg' => '请等待学校和/或组织团队审核！']);  
            if (Reg::current()->specific()->status != 'paid')//TO-DO: parameters for this  
                return view('error', ['msg' => '请先缴费！如果您已通过社团缴费，请等待组织团队确认']); 
        }
        $committee = Reg::current()->specific()->committee;
        return view('documentsList', ['type' => Reg::current()->type]);
    }
    
    /**
     * Perform actions related to one document.
     *
     * @param int $id the ID of the document
     * @param string $action the action on the document to be performed
     * @return \Illuminate\Http\Response
     */
    public function document($id, $action = "")
    {
        $document = Document::findOrFail($id);
        if (Reg::current()->type == 'unregistered' || Reg::current()->type == 'volunteer')
            return view('error', ['msg' => '您不是参会代表，无权访问该页面！']);
        else if (Reg::current()->type == 'delegate')
        {
            if (!$document->belongsToDelegate(Reg::current()->id))
                return view('error', ['msg' => '您不是此学术文件的分发对象，无权访问该页面！']);
        }
        if ($action == "download")
        {
            $document->downloads++;
            $document->save();
            return response()->download(storage_path('/app/'.$document->path), $document->title . '.' . File::extension(storage_path('/app/'.$document->path)));
        }
        else if ($action == "raw")
        {
            return response()->file(storage_path('/app/'.$document->path));
        }
        else if ($action == "upload")
        {
            if (Reg::current()->type != 'ot' || Reg::current()->type != 'dais')
                return view('error', ['msg' => '您不是该会议学术团队成员，无权对文件操作！']);
            // $document->downloads = 0;
            // $document->views = 0;
            // TODO: 完成文件上传
        }
        else
        {
            $document->views++;
            $document->save();
            return view('documentInfo', ['document' => $document]);
        }
    }
    
    /**
     * Show the details modal of one document.
     *
     * @param int $id the ID of the document
     * @return \Illuminate\Http\Response
     */
    public function documentDetailsModal($id)
    {
        if ($id == 'new')
        {
            $document = new Document;
            $document->title = 'New document';
            $document->description = '请在此输入对该学术文件的描述';
            $document->path = 'default/no-docs.pdf';
            $document->save();
        }
        else
            $document = Document::findOrFail($id);
        return view('documentDetailsModal', ['document' => $document]);
    }
    
    /**
     * Show the role/delegate list of a committee.
     *
     * @param string $view whether to display list of role or delegate
     * @return \Illuminate\Http\Response
     */
    public function roleList($view = 'nation')
    {
        if (Reg::current()->type == 'delegate' && Reg::current()->delegate->committee->is_allocated == false)
            return view('error', ['msg' => '请等待席位分配发布！']);
        return view('roleList', ['view' => $view]);
    }
    
    /**
     * Show the role allocation page for Dais and Organizing Team
     *
     * @return \Illuminate\Http\Response
     */
    public function roleAlloc()
    {
        if (Reg::current()->type == 'delegate')
        {
            if (!Reg::current()->specific()->committee->is_allocated)
                return view('error', ['msg' => '您不是该会议学术团队成员，无权进行席位分配！']);
            else
                return redirect(secure_url('/roleList'));
        }
        else if (Reg::current()->type == 'ot')
            return redirect(secure_url('/nationManage'));
        else if (Reg::current()->type != 'dais')
            return view('error', ['msg' => '您不是该会议学术团队成员，无权进行席位分配！']);            
        if (Reg::current()->specific()->committee->is_allocated)
            return redirect(secure_url('/roleList'));
        $mycommittee = Reg::current()->dais->committee;
        return view('dais.roleAlloc', [
            'committee' => $mycommittee, 
            'mustAlloc' => $mycommittee->delegates->where('status', 'paid')->where('nation_id', null)->count(), 
            'emptyNations' => $mycommittee->emptyNations()->count(),
            'verified' => Delegate::where(function($query) {$query->where('committee_id', Reg::current()->dais->committee->id)->where('status', 'paid');})->orWhere(function($query) {$query->where('committee_id', Reg::current()->dais->committee->id)->where('status', 'oVerified');})->count(),
            'isDouble' => true
        ]);
    }

    /**
     * Show the email verification page for unverified users.
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyEmail()
    {
        if (Auth::user()->emailVerificationToken == 'success')
            return redirect('/home');
        return view('verifyEmail');
    }

    /**
     * Show the phone verification page for unverified users.
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyTel()
    {
        if (Auth::user()->telVerifications == -1) //3/2/1: tries left; -1: activated
            return redirect(secure_url('/home'));
        return view('verifyTel');
    }

}
