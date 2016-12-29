<?php

namespace App\Http\Controllers;

use App\User;
use App\Delegate;
use App\Volunteer;
use App\Observer;
use App\School;
use App\Committee;
use App\Permission;
use App\Role;
use App\Assignment;
use App\Delegategroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
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

    public function regSaveDel(Request $request)
    {
        //$user = Auth::user();
        $user = User::find($request->id);
        if (is_null($request->id))
            $user = Auth::user();
        else if (Auth::user()->type == 'ot' && (!Auth::user()->can('edit-regs')))
            return view('dialogErrorModal', ['msg' => '您无权进行该操作！']);
        else if (Auth::user()->type != 'ot' && Auth::user()->type != 'school')
            return view('dialogErrorModal', ['msg' => '您无权进行该操作！']);
        else if (Auth::user()->type == 'school' && Auth::user()->school->id != $user->specific()->school->id)
            return view('dialogErrorModal', ['msg' => '您无权对非本校参会人员进行操作！']);
        $user->type = 'delegate';
        $user->save();
        $del = $user->delegate;
        if (is_null($del))
        {
            $del = new Delegate;
            $del->user_id = $user->id;
            $del->email = $user->email;
        }
        if (Auth::user()->type != 'school')
            $del->school_id = $request->school;
        else
            $del->school_id = Auth::user()->school->id;
        if (Auth::user()->type != 'ot' || $del->status == null)
            $del->status = 'reg';
        $del->gender = $request->gender;
        $del->sfz = $request->sfz;
        $del->grade = $request->grade;
        $del->qq = $request->qq;
        $del->wechat = $request->wechat;
        $del->partnername = $request->partnername;
        $del->parenttel = $request->parenttel;
        $del->tel = $request->tel;
        $del->committee_id = $request->committee;
        $del->accomodate = $request->accomodate;
        $del->roommatename = $request->roommatename;
        $del->save();
        Volunteer::destroy($user->id);
        Observer::destroy($user->id);
    }

    public function regSaveVol(Request $request)
    {
        //$user = Auth::user();
        $user = User::find($request->id);
        if (is_null($request->id))
            $user = Auth::user();
        else if (Auth::user()->type == 'ot' && (!Auth::user()->can('edit-regs')))
            return view('dialogErrorModal', ['msg' => '您无权进行该操作！']);
        else if (Auth::user()->type != 'ot' && Auth::user()->type != 'school')
            return view('dialogErrorModal', ['msg' => '您无权进行该操作！']);
        else if (Auth::user()->type == 'school' && Auth::user()->school->id != $user->specific()->school->id)
            return view('dialogErrorModal', ['msg' => '您无权对非本校参会人员进行操作！']);
        $user->type = 'volunteer';
        $user->save();
        $vol = $user->volunteer;
        if (is_null($vol))
        {
            $vol = new Volunteer;
            $vol->user_id = $user->id;
            $vol->email = $user->email;
        }
        if (Auth::user()->type != 'school')
            $vol->school_id = $request->school;
        else
            $vol->school_id = Auth::user()->school->id;
        if (Auth::user()->type != 'ot' || $vol->status == null)
            $vol->status = 'reg';
        $vol->gender = $request->gender;
        $vol->sfz = $request->sfz;
        $vol->grade = $request->grade;
        $vol->qq = $request->qq;
        $vol->wechat = $request->wechat;
        $vol->parenttel = $request->parenttel;
        $vol->tel = $request->tel;
        $vol->accomodate = $request->accomodate;
        $vol->roommatename = $request->roommatename;
        $vol->save();
        Delegate::destroy($user->id);
        Observer::destroy($user->id);
    }

    public function regSaveObs(Request $request)
    {
        //$user = Auth::user();
        $user = User::find($request->id);
        if (is_null($request->id))
            $user = Auth::user();
        else if (Auth::user()->type == 'ot' && (!Auth::user()->can('edit-regs')))
            return view('dialogErrorModal', ['msg' => '您无权进行该操作！']);
        else if (Auth::user()->type != 'ot' && Auth::user()->type != 'school')
            return view('dialogErrorModal', ['msg' => '您无权进行该操作！']);
        else if (Auth::user()->type == 'school' && Auth::user()->school->id != $user->specific()->school->id)
            return view('dialogErrorModal', ['msg' => '您无权对非本校参会人员进行操作！']);
        $user->type = 'observer';
        $user->save();
        $obs = $user->observer;
        if (is_null($obs))
        {
            $obs = new Observer;
            $obs->user_id = $user->id;
            $obs->email = $user->email;
        }
        $obs->school_id = $request->school;
        if (Auth::user()->type != 'ot' || $obs->status == null)
            $obs->status = 'reg';
        $obs->gender = $request->gender;
        $obs->sfz = $request->sfz;
        $obs->grade = $request->grade;
        $obs->qq = $request->qq;
        $obs->wechat = $request->wechat;
        $obs->parenttel = $request->parenttel;
        $obs->tel = $request->tel;
        $obs->accomodate = $request->accomodate;
        $obs->roommatename = $request->roommatename;
        $obs->save();
        Delegate::destroy($user->id);
        Volunteer::destroy($user->id);
    } 

    public function schoolVerify($id)
    {
        $school = Auth::user()->school;
        $specific =  User::find($id)->specific();
        if ($specific->school->id != $school->id)
            return view('dialogdialogErrorModalModal', ['msg' => '您无权对非本校参会人员进行操作！']);
        $specific->status = 'sVerified';
        $specific->save();
    }

    public function schoolUnverify($id)
    {
        $school = Auth::user()->school;
        $specific =  User::find($id)->specific();
        if ($specific->school->id != $school->id)
            return view('dialogdialogErrorModalModal', ['msg' => '您无权对非本校参会人员进行操作！']);
        $specific->status = 'reg';
        $specific->save();
    }

    public function setStatus($id, $status)
    {
        if (Auth::user()->type != 'ot' || (!Auth::user()->can('approve-regs')))
            return view('dialogdialogErrorModalModal', ['msg' => '您无权进行该操作！']);
        if ($status == 'paid' && (!Auth::user()->can('approve-regs-pay')))
            return view('dialogdialogErrorModalModal', ['msg' => '您无权更改该参会人员的缴费信息！']);
        $specific =  User::find($id)->specific();
        $specific->status = $status;
        $specific->save();
    }


    public function regSchool()
    {
if (($handle = fopen("/var/www/munpanel/test.csv", "r")) !== FALSE) {
    $resp = "";
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        for ($c=0; $c < $num; $c++) {
            $resp = $resp. $data[$c] . "<br />\n";
        }
        $user = new User;
        $user->name = $data[0];
        $user->password = Hash::make($data[1]);
        $user->email = $data[0]. '@schools.bjmun.org';
        $user->type = 'school';
        $user->save();
        $school = School::where('name', $data[0])->first();
        $school->user_id =$user->id;
        $school->save();
        $resp = $resp. response()->json($user) . "<br />\n";
        $resp = $resp. response()->json($school) . "<br />\n";
    }
    fclose($handle);
    return $resp;
}
    }

    public function doChangePwd(Request $request)
    {
        $user = Auth::user();
        if (Hash::check($request->oldPassword, $user->password))
        {
            $user->password = Hash::make($request->newPassword);
            $user->save();
            return redirect(secure_url('/home'));
        }
        else
            return view('dialogdialogErrorModalModal', ['msg' => '您输入的密码有误，请重试！']);
    }

    public function updateUser(Request $request, $id)
    {
        if (Auth::user()->type != 'ot')
            return view('dialogdialogErrorModalModal', ['msg' => '您不是该会议组织团队成员，无权进行该操作！']);
        $user = User::findOrFail($id);
        $name = $request->get('name');
        $value = $request->get('value');
        if ($name == 'password')
            $value = Hash::make($value);
        if ($name == 'type')
        {
            Delegate::destroy($user->id);
            Volunteer::destroy($user->id);
            Observer::destroy($user->id);
        }
        $user->$name = $value;
        $user->save();
    }

    public function updateSchool(Request $request, $id)
    {
        if (Auth::user()->type != 'ot')
            return view('dialogdialogErrorModalModal', ['msg' => '您不是该会议组织团队成员，无权进行该操作！']);
        $school = School::findOrFail($id);
        $name = $request->get('name');
        $value = $request->get('value');
        $school->$name = $value;
        $school->save();
    }

    public function updateCommittee(Request $request, $id)
    {
        if (Auth::user()->type != 'ot')
            return view('dialogdialogErrorModalModal', ['msg' => '您不是该会议组织团队成员，无权进行该操作！']);
        $committee = Committee::findOrFail($id);
        $name = $request->get('name');
        $value = $request->get('value');
        $committee->$name = $value;
        $committee->save();
    }

    public function deleteUser(Request $request, $id)
    {
        if (Auth::user()->type != 'ot')
            return view('dialogdialogErrorModalModal', ['msg' => '您不是该会议组织团队成员，无权进行该操作！']);
        User::destroy($id);
    }

    public function deleteCommittee(Request $request, $id)
    {
        if (Auth::user()->type != 'ot')
            return view('dialogdialogErrorModalModal', ['msg' => '您不是该会议组织团队成员，无权进行该操作！']);
        Committee::destroy($id);
    }

    public function deleteSchool(Request $request, $id)
    {
        if (Auth::user()->type != 'ot')
            return view('dialogdialogErrorModalModal', ['msg' => '您不是该会议组织团队成员，无权进行该操作！']);
        School::destroy($id);
    }

    public function createPermissions()
    {
        /*$editUser = new Permission();
        $editUser->name = 'edit-users';
        $editUser->display_name = '用户管理';
        $editUser->description = '添加、删除、编辑用户的登陆信息、权限';
        $editUser->save();

        $editRole = new Permission();
        $editRole->name = 'edit-roles';
        $editRole->display_name = '角色管理';
        $editRole->description = '添加、删除角色，修改角色所含权限';
        $editRole->save();

        $viewReg = new Permission();
        $viewReg->name = 'view-regs';
        $viewReg->display_name = '报名信息查看';
        $viewReg->description = '查看代表、志愿者、观察员的报名信息';
        $viewReg->save();

        $editReg = new Permission();
        $editReg->name = 'edit-regs';
        $editReg->display_name = '报名信息编辑';
        $editReg->description = "编辑代表、志愿者、观察员的报名信息";
        $editReg->save();

        $approveReg = new Permission();
        $approveReg->name = 'approve-regs';
        $approveReg->display_name = '报名信息审核';
        $approveReg->description = '修改代表、志愿者、观察员的报名状态（不能修改为已缴费）';
        $approveReg->save();

        $approvePay = new Permission();
        $approvePay->name = 'approve-regs-pay';
        $approvePay->display_name = '报名缴费审核';
        $approvePay->description = '修改代表、志愿者、观察员的报名状态为已缴费（需要拥有报名信息审核权限）';
        $approvePay->save();

        $editCom = new Permission();
        $editCom->name = 'edit-committees';
        $editCom->display_name = '委员会管理';
        $editCom->description = '添加、删除、编辑委员会';
        $editCom->save();

        $editSchool = new Permission();
        $editSchool->name = 'edit-schools';
        $editSchool->display_name = '学校管理';
        $editSchool->description = '添加、删除、编辑学校';
        $editSchool->save();*/


        $editUser = Permission::find(1);
        $editRole = Permission::find(2);
        $viewReg = Permission::find(3);
        $editReg = Permission::find(4);
        $approveReg = Permission::find(5);
        $approvePay = Permission::find(6);
        $editCom = Permission::find(7);
        $editSchool = Permission::find(8);

        /*$sysadmin = new Role();
        $sysadmin->name = 'sysadmin';
        $sysadmin->display_name = '系统管理员';
        $sysadmin->description = '包括所有权限。一般不应使用此角色而应使用若干子角色结合。';
        $sysadmin->save();*/

        $sysadmin = Role::find(1);

        $sysadmin->attachPermissions(array($editUser, $editRole, $viewReg, $editReg, $approveReg, $approvePay, $editCom, $editSchool));
        User::where('email', '=', 'yixuan@bjmun.org')->first()->attachRole($sysadmin);

    }

    public function test()
    {
        $schools = School::all();
        foreach($schools as $school)
        {
            if ($school->user_id != 1)
            {
                $school->payment_method = 'group';
                $school->save();
            }
        }
        return 'ha';
        $assignment = new Assignment;
        $assignment->subject_type = 'individual';
        $assignment->handin_type = 'upload';
        $assignment->title = '非成员校代表 ECOSOC报名学术测试';
        $assignment->description = '<h4>学术测试题目</h4>城市及其他人类住区作为经济发展的引擎，推动减贫，带动区域经济的增长和发展。但与此同时，随着城市化水平的提高，环境污染、相应的基础设施不足、社会动荡等问题也日益凸显。请从可持续发展的角度，针对于城市化带来的三个不同方面的问题提出解决措施并分析理由。<br><br><h4>学术测试要求</h4>请有搭档的代表由二人合作共同完成一份学术测试，目前没有搭档的代表请自行完成本次测试。二人合作共同完成的，请在学术测试答案文件中注明双人姓名，仅需一个人提交系统即可。<br><br><h4>学术诚信要求</h4>本学术测试均请各位代表<u>独立</u>完成，学术测试的全部内容需是撰写学术测试者自行完成的结果，请勿使撰写学术测试者之外的任何人对于学术测试参与包括但不限于：撰写、部分撰写、修改、点评等影响学术测试的行为，一经发现将被视为学术不端进行处理。<br><br>在学术测试撰写时，鼓励各位代表进行各类资料的查阅。但主席团禁止任何形式的抄袭，若在学术测试的撰写过程中需要对于资料进行参考或引用，请在文中以脚注形式标注出引用文段，并在文后列举撰写过程中全部的参考资料。若对于学术资料进行引用但未标注，也会同样被认定为抄袭。<br><br><u>在学术测试当中，被发现有任何学术不端行为的代表将不予录取。</u><br><br><h4>参考与引用标注方式</h4>书籍类：作者：《文献名》，出版社，出版年，页码。<br>论文与报刊类：作者：《文献名》，《刊物名》和期数。<br>外文类：作者，文献名（斜体），出版地：出版社或报刊名，时间，页码。<br>网络内容：文章主题，网络链接<br>其他形式的参考引用内容请自行注明<br><br>学术测试将会在提交之后由主席团批阅后择优录取。';
        $assignment->deadline = '2017-01-01 23:59:59';
        $assignment->save();
        $delegategroup = Delegategroup::find(4);
        $delegategroup->assignments()->attach($assignment);
        return '...';
        $delegategroup = new Delegategroup;
        $delegategroup->name = '非成员校ECOSOC';
        $delegategroup->display_name = '非成员校ECOSOC代表';
        $delegategroup->save();
        $delegategroup = Delegategroup::find(1);
        $delegates = Committee::find(1)->delegates;
        $delegates->load('school');
        foreach ($delegates as $delegate)
                if ($delegate->school->user_id != 1 && $delegategroup->delegates()->find($delegate->user_id) === null)
                        $delegategroup->delegates()->attach($delegate);
        /*$delegates = Committee::find(2)->delegates;
        $delegates->load('school');
        foreach ($delegates as $delegate)
                if ($delegate->school->user_id == 1)
                        $delegategroup->delegates()->attach($delegate);*/
        return 'hello';
        return Auth::user()->delegate->nation->name;
        return Assignment::find(1)->belongsToDelegate(9);
        return response()->json(Auth::user()->delegate->assignments());
        return Auth::user()->invoiceAmount();
        return Auth::user()->invoiceItems();
        return "gou";
    }
}
