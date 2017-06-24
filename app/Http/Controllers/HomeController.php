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

use App\Conference;
use App\Committee;
use App\School;
use App\Delegate;
use App\Volunteer;
use App\Observer;
use App\Dais;
use App\Orgteam;
use App\User;
use App\Assignment;
use App\Handin;
use App\Form;
use App\Document;
use App\Email;
use App\Reg;
use App\Order;
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
    public function index(Request $request)
    {
        $activep['home'] = true;
        $reg = Reg::current();
        $type = $reg->type;
        $committees = Reg::currentConference()->committees;
        $hasChildComm = false;
        foreach ($committees as $committee)
            if ($committee->childCommittees->count() > 0)
            {
                $hasChildComm = true;
                break;
            }
        if ($type == 'ot' && $reg->specific()->status == 'success')
        {
            return view('ot.home', [
                'committees' => Reg::currentConference()->committees,
                'vol' => Reg::currentConference()->volunteers->count(),
                'obs' => Reg::currentConference()->observers->count(),
                'del' => Reg::currentConference()->delegates->count(),
                'vol_real' => Reg::currentConference()->volunteers->whereIn('status', ['sVerified', 'oVerified', 'paid'])->count(),
                'obs_real' => Reg::currentConference()->observers->whereIn('status', ['sVerified', 'oVerified', 'paid'])->count(),
                'del_real' => Reg::currentConference()->delegates->whereIn('status', ['sVerified', 'oVerified', 'paid'])->count(),
                'dais' => Reg::currentConference()->dais->count(),
                'hasChildComm' => $hasChildComm,
                'initialModal' => $request->initmodal
            ]);
        }
        else if ($type == 'teamadmin')
        {
            $school = $reg->school;
            //return $del->count();
            return view('school.home', ['del' => $school->delegates()->where('conference_id', Reg::currentConferenceID())->count(), 'vol' => $school->volunteers()->where('conference_id', Reg::currentConferenceID())->count(), 'obs' => $school->observers()->where('conference_id', Reg::currentConferenceID())->count()]);
        }
        else if ($type == 'dais' && $reg->specific()->status == 'success')
        {
            return view('dais.home', [
                'vol' => Reg::currentConference()->volunteers->count(),
                'obs' => Reg::currentConference()->observers->count(),
                'del' => Reg::currentConference()->delegates->count(),
                'dais' => Reg::currentConference()->dais->count(),
                'hasChildComm' => $hasChildComm,
                'initialModal' => $request->initmodal
            ]);
        }
        else if ($type == 'interviewer')
        {
            return view('interviewer.home', [
                'vol' => Reg::currentConference()->volunteers->count(),
                'obs' => Reg::currentConference()->observers->count(),
                'del' => Reg::currentConference()->delegates->count(),
                'dais' => Reg::currentConference()->dais->count(),
                'hasChildComm' => $hasChildComm,
                'initialModal' => $request->initmodal,
            ]);
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
                if (in_array($type, ['dais', 'ot']))
                {
                    return redirect(mp_url('/daisregForm'));
                }
            }
            else if ($specific->status == 'sVerified')
            {
                $percent = 50;
                $status = '等待组织团队审核';
            }
            else if ($specific->status == 'oVerified')
            {
                $percent = 75;
                $status = '待缴费';
                $changable = false;
                if ($type == 'dais')
                {
                    $percent = 75;
                    $status = '等待面试';
                    if (!$reg->enabled)
                    {
                        $percent = 0;
                        $status = '面试未通过';
                    }
                }
            }
            else if ($specific->status == 'fail')
            {
                $percent = 0;
                $status = '未通过';
                $changable = false;
            }
            else
            {
                $percent = 100;
                $status = "已缴费";
                $changable = false;
            }

            if (isset($notice_msg))
                return view('home', ['percent' => $percent, 'status' => $status, 'changable' => $changable, 'notice_msg' => $notice_msg, 'initialModal' => $request->initmodal]);
            else
                return view('home', ['percent' => $percent, 'status' => $status, 'changable' => $changable, 'initialModal' => $request->initmodal]);
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
        $regDate = json_decode(Reg::currentConference()->option('reg_dates'));
        $select = ['delegateUse' => false, 'observerUse' => false, 'volunteerUse' => false, 'daisUse' => false, 'otUse' => false, 'delegateMsg' => '不可用', 'observerMsg' => '不可用', 'volunteerMsg' => '不可用', 'daisMsg' => '不可用', 'otMsg' => '不可用'];
        foreach ($regDate as $value)
        {
            if (strtotime(date("y-m-d h:i:s")) < strtotime($value->config->start)) $select[$value->use.'Msg'] = '暂未开启';
            elseif ($value->config->end == 'ended') $select[$value->use.'Msg'] = '已关闭';
            elseif (strtotime(date("y-m-d h:i:s")) > strtotime($value->config->end) && $value->config->end != 'manual') $select[$value->use.'Msg'] = '已结束';
            else
            {
                unset($select[$value->use.'Msg']);
                $select[$value->use.'Use'] = true;
            }
        }
        if ($regType == 'select')
        {
            if (Reg::currentConference()->status == 'daisreg')
                return view('daisregSelectModal', $select);
            return view('regSelectModal', $select);
        }
        $customTable = json_decode(Reg::currentConference()->option('reg_tables'))->regTable;
        $confForm = FormController::render($customTable->conference->items, $regType, 'uses');
        return view('reg2Modal', ['regType' => $regType, 'customTable' => $customTable, 'confForm' => $confForm]);
    }

    /**
     * Show the dais registration form modal.
     * Dynamic form, used in BJMUNSS 2017.
     *
     * @return \Illuminate\Http\Response
     */
    public function daisregModal()
    {
        $customTable = json_decode(Reg::currentConference()->option('reg_tables'))->daisregTable; //todo: table id
        $confForm = FormController::render($customTable->conference->items, 'dais');
        return view('reg2Modal', ['regType' => 'dais', 'customTable' => $customTable, 'confForm' => $confForm]);
    }

    /**
     * Show the dais registration form modal.
     * Dynamic form, used in BJMUNSS 2017.
     *
     * @return \Illuminate\Http\Response
     */
    public function otregModal()
    {
        $customTable = json_decode(Reg::currentConference()->option('reg_tables'))->regTable; //todo: table id
        $confForm = FormController::render($customTable->conference->items, 'ot');
        return view('reg2Modal', ['regType' => 'ot', 'customTable' => $customTable, 'confForm' => $confForm]);
    }

    /**
     * Show the registration management page.
     *
     * @return \Illuminate\Http\Response
     */
    static public function regManage(Request $request)
    {
        $type = Reg::current()->type;
        if (Reg::current()->can('view-regs'))
        {
            if (isset($request->initialReg))
                return view('ot.regManage', ['delegates' => Delegate::all(), 'volunteers' => Volunteer::all(), 'observers' => Observer::all(), 'initialModal' => mp_url('/ot/regInfo.modal/'. $request->initialReg)]);
            return view('ot.regManage', ['delegates' => Delegate::all(), 'volunteers' => Volunteer::all(), 'observers' => Observer::all()]);
        }
        else if ($type == 'teamadmin')
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
    public function teamManage()
    {
        if ( Reg::current()->type != 'ot' )
            return 'Error';
        return view('ot.teamManage');
    }

    /**
     * Show the user management page.
     *
     * @return \Illuminate\Http\Response
     */
    public function userManage()
    {
        if (!Reg::current()->can('edit-users'))
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
        if (!Reg::current()->can('edit-schools'))
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
        if (!Reg::current()->can('edit-committees'))
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
        if (!Reg::current()->can('edit-nations'))
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
        if (!Reg::current()->can('edit-users'))
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
        $reg = Reg::findOrFail($id);
        //WTF!!! WHY DON'T WE HAVE THIS VERIFICATION BEFORE???
        if ($id != Reg::currentID() && !in_array(Reg::current()->type, ['ot', 'dais', 'interviewer', 'teamadmin']))
            return 'error';
        if (Reg::current()->type == 'teamadmin' && $reg->school_id != Reg::current()->school_id)
            return 'error';
        $allRegs = Reg::where('user_id', $reg->user_id)->where('conference_id', $reg->conference_id)->get(['id', 'type']);
        $operations = array();
        if (in_array(Reg::current()->type, ['ot', 'dais', 'interviewer'])) {
            if (Reg::current()->can('sudo'))
                $operations[] = 'sudo';
            if ($reg->type == 'delegate')
            {
                if ($reg->delegate->canAssignSeats() && isset($reg->delegate->nation_id))
                    if (!$reg->delegate->seat_locked)
                        $operations[] = 'lockSeat';
                    else
                        $operations[] = 'unlockSeat';
                $interviewStatus = $reg->delegate->interviewStatus();
                $status = $reg->delegate->status;
                if (Reg::current()->can('view-regs')) {
                    switch($interviewStatus)
                    {
                        case 'interview_assigned':break;
                        case 'interview_passed':
                        case 'interview_failed':
                        case 'interview_retest_passed':
                        case 'interview_retest_failed':
                        case 'interview_retest_unassigned':
                        case 'interview_unassigned': if (Reg::current()->can('view-all-interviews')) $operations[] = 'assignInterview'; break;
                    }
                    if ($reg->enabled)
                    {
                        switch($status)
                        {
                            case 'sVerified': if ($reg->enabled) $operations[] = 'oVerification'; break;
                            case 'fail': if ($reg->enabled) $operations[] = 'oReverification'; break;
                        }
                    }
                }
                if (Reg::current()->type == 'ot' || Reg::current()->type == 'dais')
                {
                    if ($reg->delegate->status != 'fail') $operations[] = 'moveCommittee';
                    if ($reg->delegate->status != 'fail' && Reg::currentConference()->delegategroups->count() > 0) $operations[] = 'setDelgroup';
                }
            } else if ($reg->specific()->status == 'sVerified')
                $operations[] = 'oVerification';
        }
        /*else if (Reg::current()->type == 'teamadmin') {
            if ($reg->specific()->status == 'sVerified')
                $operations[] = 'sVerification';
        }*/
        return view('ot.regInfoModal', ['reg' => $reg, 'allRegs' => $allRegs, 'operations' => $operations]);
    }

    /**
     * Show the dais registration info modal.
     *
     * @param int $id UID of dais queried
     * @return \Illuminate\Http\Response
     */
    public function daisregInfoModal($id)
    {
        $reg = Reg::findOrFail($id);
        if (!in_array($reg->type, ['ot', 'dais']))
            return 'error';
        $allRegs = Reg::where('user_id', $reg->user_id)->where('conference_id', $reg->conference_id)->get(['id', 'type']);
        $operations = array();
        $status = $reg->specific()->status;
        $answer = json_decode($reg->specific()->handin);
        $html = $formName = '';
        if (isset($answer->_token))
        {
            $form = Form::findOrFail($answer->form);
            $formCt = json_decode($form->content);
            $formName = $form->name;
            $html = FormController::getMyAnswer($formCt->items, $answer);
        }
        switch($status)
        {
            case 'sVerified': $operations[] = 'oVerification'; break;
        }
        return view('ot.daisregInfoModal', ['reg' => $reg, 'allRegs' => $allRegs, 'operations' => $operations, 'formContent' => $html, 'formTitle' => $formName]);
    }

    /**
     * Show the school details modal.
     *
     * @param int $id ID of school queried
     * @return \Illuminate\Http\Response
     */
    public function schoolDetailsModal($id)
    {
        if (!Reg::current()->can('edit-schools'))
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
        if (!Reg::current()->can('edit-committees'))
            return "Error";
        if ($id == 'new')
        {
            $committee = new Committee;
            $committee->name = 'New Committee';
            $committee->conference_id = Reg::currentConferenceID();
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
        return redirect(mp_url('/school/payment'));
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
        $order = Order::findOrFail($id);
        $custom = $order->conference->option('store_custom_pay_methods');
        if (isset($custom))
            $custom = json_decode($custom, true);
        else
            $custom = array();
        return view('checkoutModal', ['id' => $id, 'custom' => $custom]);
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
            //if (Reg::current()->specific()->status != 'paid')//TO-DO: parameters for this
                //return view('error', ['msg' => '请先缴费！如果您已通过社团缴费，请等待组织团队确认']);
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
        if (Reg::current()->type != 'ot' && Reg::current()->type != 'dais' && (!$assignment->belongsToDelegate(Reg::currentID())))
            return "ERROR"; //TO-DO: Permission check for ot and dais (for downloading handins)
        if (in_array(Reg::current()->type, ['ot', 'dais']))
            $handins = $assignment->handins;
        else if ($assignment->subject_type == 'nation')
            $handin = Handin::where('assignment_id', $id)->where('nation_id', Reg::current()->delegate->nation->id)->orderBy('id', 'desc')->first();
        else if ($assignment->subject_type == 'partner')
        {
            if (isset(Reg::current()->delegate->partner)) $handin = Handin::where('assignment_id', $id)->where('reg_id', Reg::current()->delegate->partner->id)->orderBy('id', 'desc')->first();
            if (!isset($handin)) $handin = Handin::where('assignment_id', $id)->where('reg_id', Reg::currentID())->orderBy('id', 'desc')->first();
            else
            {
                $handin1 = Handin::where('assignment_id', $id)->where('reg_id', Reg::currentID())->orderBy('id', 'desc')->first();
                if (isset($handin1) && $handin1->id > $handin->id) $handin = $handin1;
            }
        }
        else
            $handin = Handin::where('assignment_id', $id)->where('reg_id', Reg::currentID())->orderBy('id', 'desc')->first();
        if ($action == 'info')
        {
            if (isset($handin))
            {
                $html = '';
                if ($assignment->handin_type == 'form')
                {
                    $answer = json_decode($handin->content);
                    if (empty($answer->_token)) return redirect(mp_url('/assignment/' . $id . '/form'));
                    $form = json_decode(Form::findOrFail($answer->form)->content);
                    $html = FormController::getMyAnswer($form->items, $answer);
                }
                return view('assignmentHandinInfo', ['assignment' => $assignment, 'handin' => $handin, 'formContent' => $html]);
            }
            else if (in_array($assignment->handin_type, ['upload', 'form']))
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
            return response()->download(storage_path('/app/'.$handin->content), $assignment->title.' '.$handin->reg->name().'.' . File::extension(storage_path('/app/'.$handin->content)));
        }
        else if ($action == "resubmit")
        {
            if (is_null($handin))
                return redirect(mp_url('/assignment/' . $id));
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
                $filename = $handin->reg->id . '_' . $handin->reg->name() . ' ' . date('y-m-d-H-i-s', strtotime($handin->updated_at)) . '.' . File::extension(storage_path('/app/'.$handin->content));
                $zip->addFile(storage_path('app/' . $handin->content), $filename);
                //Zipper::zip($zippername)->addString($filename, Storage::get($handin->content));
            }
            $zip->close();
            return response()->download(storage_path('app/assignmentExports/'. $zipname));
        }
        else if ($action == "form")
        {
            $form = $assignment->form->random();
            $formID = $form->id;
            $questions = FormController::getQuestions(json_decode($form->content));
            $cansave = !empty(json_decode($form->content)->config->cansave);
            $handin = Handin::where('assignment_id', $id)->where('reg_id', Reg::currentID())->orderBy('id', 'desc')->first();
            if (is_null($handin))
            {
                $handin = new Handin;
                $handin->assignment_id = $assignment->id;
                $handin->reg_id = Reg::currentID();
                $handin->handin_type = "json";
                $content = [];
                $content['form'] = $form->id;
                foreach ($questions as $item)
                    $content[$item->id] = "";
                $handin->content = json_encode((object)$content);
                $handin->save();
            }
            else
            {
                $content = json_decode($handin->content);
                if (!empty($content->_token)) return redirect(mp_url('/assignment/' . $id));
                $form = json_decode(Form::findOrFail($content->form)->content);
                $questions = FormController::restoreQuestions($form->items, $content);
                $cansave = !empty($form->config->cansave);
                $formID = $content->form;
            }
            $target = '/assignment/'.$assignment->id.'/formSubmit';
            $html = FormController::formAssignment($assignment->id, $questions, $formID, $target, $cansave, $handin);
            return view('assignmentForm', ['title' => $assignment->title, 'formContent' => $html, 'target' => $target]);
        }
    }

    public function daisregForm()
    {
        if (!in_array(Reg::current()->type, ['dais', 'ot']))
            return view('error', ['msg' => '您不是学术团队或会务团队申请者，无权访问该页面！']);
        $handin = json_decode(Reg::current()->specific()->handin);
        $formID = 0;
        if (isset($handin->form))
            $formID = $handin->form;
        else
        {
            $language = $handin->language;
            $forms = json_decode(Reg::currentConference()->option('reg_tables'))->daisregForms;
            foreach ($forms as $formCfg)
            {
                if ($formCfg->language == $language)
                {
                    $formID = $formCfg->formID;
                    break;
                }
            }
        }
        $form = Form::findOrFail($formID);
        $questions = FormController::getQuestions(json_decode($form->content));/*
        if (is_null($handin))
        {
            $content = [];
            $content['form'] = $form->id;
            foreach ($questions as $item)
                $content[$item->id] = "";
            $handin->content = json_encode((object)$content);
            $handin->save();
        }
        else
        {
            $content = json_decode($handin);
            if (!empty($content->_token)) return redirect(mp_url('/assignment/' . $id));
            $form = json_decode(Form::findOrFail($content->form)->content);
            $questions = FormController::restoreQuestions($form->items, $content);
            $formID = $content->form;
        }*/
        $target = '/daisregForm/formSubmit';
        $html = FormController::daisregformAssignment($questions, $formID, $target, $handin);
        return view('assignmentForm', ['title' => $form->name, 'formContent' => $html, 'target' => $target]);
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
        if (!$assignment->belongsToDelegate(Reg::currentID()))
            return "ERROR";
        if (strtotime(date("y-m-d H:i:s")) >= strtotime($assignment->deadline))
            return "ERROR";
        if ($request->hasFile('file') && $request->file('file')->isValid())
        {
            $handin = new Handin;
            if ($assignment->subject_type == 'nation')
                $handin->nation_id = Reg::current()->delegate->nation->id;
            //else
            $handin->reg_id = Reg::currentID();
            $handin->content = $request->file->store('assignmentHandins');
            $handin->confirm = true;
            $handin->assignment_id = $id;
            $handin->handin_type = 'upload';
            $handin->remark = $request->remark;
            $handin->save();
            return redirect(mp_url('/assignment/' . $id));
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
        return view('ot.imexportModal', ['importURL' => mp_url('/regManage/import'), 'exportURL' => mp_url('/regManage/export')]);
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
            //if (Reg::current()->specific()->status != 'paid')//TO-DO: parameters for this
            //    return view('error', ['msg' => '请先缴费！如果您已通过社团缴费，请等待组织团队确认']);
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
            if (!$document->belongsToDelegate(Reg::currentID()))
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
        //To-Do: permission check
        if ($id == 'new')
        {
            $document = new Document;
            $document->title = 'New document';
            $document->description = '请在此输入对该学术文件的描述';
            $document->path = 'default/no-docs.pdf';
            $document->conference_id = Reg::currentConferenceID();
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
                return redirect(mp_url('/roleList'));
        }
        /*
        else if (Reg::current()->type == 'ot')
            return redirect(mp_url('/nationManage'));
        else if (Reg::current()->type != 'dais')
            return view('error', ['msg' => '您不是该会议学术团队成员，无权进行席位分配！']);
        if (Reg::current()->specific()->committee->is_allocated)
            return redirect(mp_url('/roleList'));
            */
        //$mycommittee = Reg::current()->dais->committee;

        if (!in_array(Reg::current()->type, ['ot', 'dais', 'interviewer']))
            return view('error', ['msg' => '您没有权限分配席位！']);
        return view('dais.roleAlloc', [
            /*'committee' => $mycommittee,
            'mustAlloc' => $mycommittee->delegates->where('status', 'paid')->where('nation_id', null)->count(),
            'emptyNations' => $mycommittee->emptyNations()->count(),
            'verified' => Delegate::where(function($query) {$query->where('committee_id', Reg::current()->dais->committee->id)->where('status', 'paid');})->orWhere(function($query) {$query->where('committee_id', Reg::current()->dais->committee->id)->where('status', 'oVerified');})->count(),
            'isDouble' => true*/
        ]); // we don't use those variables anymore
    }

    /**
     * Show the email verification page for unverified users.
     *
     * @return \Illuminate\Http\Response
     */
    public function verifyEmail()
    {
        if (Auth::user()->emailVerificationToken == 'success')
            return redirect()->intended('/verifyTel');
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
            return redirect()->intended(mp_url('/home'));
        return view('verifyTel');
    }

    /**
     *
     */
    public function firstModal()
    {
        if (!Reg::selectConfirmed()) {
            return redirect(mp_url('/selectIdentityModal'));
        }
        if (!Auth::user()->verified())
            return view('verificationModal');
        if (Reg::current()->type == 'unregistered')
            return redirect(mp_url('/reg2.modal/select'));
        if (is_null(Reg::current()->specific()))
            return redirect(mp_url('/ecosocEasterEgg.modal'));
        if (Reg::current()->specific()->status == 'fail')
            return redirect(mp_url('/regNotVerified.modal'));
        if (in_array(Reg::current()->type, ['delegate', 'volunteer']) && !isset(Reg::current()->accomodate))
            return redirect(mp_url('/setAccomodation.modal'));
        return redirect(mp_url('/regAssignment.modal'));
    }

    /**
     *
     */
    public function selectIdentityModal()
    {
        return view('selectIdentityModal');
    }

    /**
     *
     */
    public function regAssignmentModal()
    {
        return view('regAssignmentModal');
    }

    /**
     *
     */
    public function ecosocEasterEggModal()
    {
        return view('ecosocEasterEggModal');
    }

    /**
     *
     */
    public function setAccomodationModal()
    {
        $json = '[{"uses":["delegate","observer","volunteer"],"type":"preIsAccomodate"},{"uses":["delegate","observer","volunteer"],"type":"preRoommateName"}]';
        $customTable = json_decode($json);
        $confForm = FormController::render($customTable, Reg::current()->type, 'uses');
        return view('setAccomodateModal', ['confForm' => $confForm]);
    }

    /**
     *
     */
    public function regNotVerifiedModal()
    {
        return view('regNotVerifiedModal');
    }

    /**
     * A function to write some temporary code.
     */
    public function blank()
    {
        $html = '<a href="groupMember/1352/admin.modal" data-toggle="ajaxModal">fuck i can\'t access test server</a>';
        return view('blank', ['testContent' => $html, 'convert' => true]);
        $i = 12;
        $test = json_decode('[
        {
            "id":"1",
            "type":"single_choice",
            "committee":["20", "23"],
            "level":"1",
            "title":"1618 年历史上第一次全欧大战爆发，战争经历了三十年，史称三十年战争，请问下列哪项是该战争带来的影响：",
            "options":[
                {"value":"1", "text":"德意志统一"},
                {"value":"2", "text":"荷兰独立"},
                {"value":"3", "text":"西班牙兴起"},
                {"value":"4", "text":"法国衰落"}
            ],
            "answer":"2"
        },
        {
            "id":"5",
            "type":"mult_choice",
            "committee":["24"],
            "level":"1",
            "title":"判断以下标题是否为符合新闻写作标准的新闻标题<br>（请从新闻写作的角度思考并作答，下同）：",
            "options":[
                {"value":"1", "text":"纽约民众抗议特朗普 拟放送对华尔街管制"},
                {"value":"2", "text":"去年普涨两三成 今年房价料回稳"},
                {"value":"3", "text":"坚守人民立场 从严管党治党"},
                {"value":"4", "text":"报告：港IPO集资额连续两年全球最高"},
                {"value":"5", "text":"普京称：俄方不会归还北方四岛，日媒还岛言论为谣言"}
            ],
            "answer":["1","4"]
        },
        {
            "id":"8",
            "type":"yes_or_no",
            "committee":["21"],
            "level":"1",
            "title":"在安理会的闭门会议中，除理事国代表以外的任何国家代表皆不得出席会议。",
            "answer":"false"
        },
        {
            "id":"11",
            "type":"fill_in",
            "committee":["21"],
            "level":"1",
            "title":"安理会所用议事规则为 (...)",
            "answer":"安理会暂行议事规则"
        },
        {
            "id":"24",
            "type":"order",
            "committee":["24"],
            "level":"1",
            "title":"假设你是新加坡联合早报的编辑，现在时间是 2016 年 11 月 16 日，请将以下几条新闻事件依报道优先级排序: ",
            "options":[
                {"value":"1", "text":"青瓦台反对朴槿惠“有序退位”"},
                {"value":"2", "text":"特朗普当选后引发安全隐忧 欧盟通过防务计划提高防御能力"},
                {"value":"3", "text":"中国遏制徇私舞弊权钱交易 高院发布量刑执法新规打击涉案权势者"},
                {"value":"4", "text":"出席台湾-亚细安对话 蔡英文谈“新向南政策”三大目标"}
            ],
            "answer":["3","4","1","2"]
        }]');
        $html = FormController::formAssignment($i, $test, 0);
        return view('blank', ['testContent' => $html, 'convert' => true]);
    }

    public function formAssignmentSubmit(Request $request, $id, $submit = false)
    {
        if ($submit == 'confirm')
            return view('warningDialogModal', ['danger' => false, 'msg' => '您将要提交当前学术作业。<br>请注意表单类型的作业一旦提交将无法再修改或撤回！<br><br>您确实要继续吗？', 'ajax' => 'post', 'target' => mp_url('/assignment/'.$id.'/formSubmit/true'), 'source' => 'assignmentForm', 'returns' => mp_url('/assignment/' . $id)]);
        $handin = Handin::findOrFail($request->handin);
        $answer = $request->all();
        if ($submit != 'true')
            unset($answer['_token']);
        else
            $handin->confirm = true;
        $handin->content = json_encode($answer);
        $handin->save();
        if ($submit == 'true')
            return redirect(mp_url('/assignment/' . $id));
    }

    public function daisregFormSubmit(Request $request, $submit = false)
    {
        $dais = Reg::current()->specific();
        $answer = $request->all();
        if ($submit == 'true')
            $dais->status = 'sVerified';
        else
            unset($answer['_token']);
        $dais->handin = json_encode($answer);
        $dais->save();
        if ($submit == 'true')
            return redirect(mp_url('/home'));
    }

    public function aboutDebug()
    {
        if (config('app.debug'))
            return view('aboutDebug');
        return redirect('/home');
    }

    public function aboutSUDO()
    {
        if (Reg::current()->user_id != Auth::id())
            return view('aboutSUDO');
        return redirect('/home');
    }
}
