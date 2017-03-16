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

use App\User;
use App\Reg;
use App\Delegate;
use App\Volunteer;
use App\Observer;
use App\School;
use App\Committee;
use App\Permission;
use App\Role;
use App\Assignment;
use App\Delegategroup;
use App\Conference;
use App\Card;
use App\Dais;
use App\Good;
use App\Order;
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

    /**
     * (Deprecated) Save delegate registration form.
     *
     * @param Request $request
     * return void
     */
    public function regSaveDel(Request $request)
    {
        //$user = Auth::user();
        $user = User::find($request->id);
        if (is_null($request->id))
            $user = Auth::user();
        else if (Reg::current()->type == 'ot' && (!Reg::current()->can('edit-regs')))
            return "error";
        else if (Reg::current()->type != 'ot' && Reg::current()->type != 'school')
            return "error";
        else if (Reg::current()->type == 'school' && Reg::current()->school->id != $user->specific()->school->id)
            return "error";
        $user->type = 'delegate';
        $user->save();
        $del = $user->delegate;
        if (is_null($del))
        {
            $del = new Delegate;
            $del->user_id = $user->id;
            $del->email = $user->email;
        }
        if (Reg::current()->type != 'school')
            $del->school_id = $request->school;
        else
            $del->school_id = Reg::current()->school->id;
        if (Reg::current()->type != 'ot' || $del->status == null)
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

    /**
     * (Deprecated) Save volunteer registration form.
     *
     * @param Request $request
     * return void
     */ 
    public function regSaveVol(Request $request)
    {
        //$user = Auth::user();
        $user = User::find($request->id);
        if (is_null($request->id))
            $user = Auth::user();
        else if (Reg::current()->type == 'ot' && (!Reg::current()->can('edit-regs')))
            return "error";
        else if (Reg::current()->type != 'ot' && Reg::current()->type != 'school')
            return "error";
        else if (Reg::current()->type == 'school' && Reg::current()->school->id != $user->specific()->school->id)
            return "error";
        $user->type = 'volunteer';
        $user->save();
        $vol = $user->volunteer;
        if (is_null($vol))
        {
            $vol = new Volunteer;
            $vol->user_id = $user->id;
            $vol->email = $user->email;
        }
        if (Reg::current()->type != 'school')
            $vol->school_id = $request->school;
        else
            $vol->school_id = Reg::current()->school->id;
        if (Reg::current()->type != 'ot' || $vol->status == null)
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

    /**
     * (Deprecated) Save observer registration form.
     *
     * @param Request $request
     * return void
     */
    public function regSaveObs(Request $request)
    {
        //$user = Auth::user();
        $user = User::find($request->id);
        if (is_null($request->id))
            $user = Auth::user();
        else if (Reg::current()->type == 'ot' && (!Reg::current()->can('edit-regs')))
            return "error";
        else if (Reg::current()->type != 'ot' && Reg::current()->type != 'school')
            return "error";
        else if (Reg::current()->type == 'school' && Reg::current()->school->id != $user->specific()->school->id)
            return "error";
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
        if (Reg::current()->type != 'ot' || $obs->status == null)
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

    /**
     * Save registration form (dynamic).
     *
     * @param Request $request
     * @return void
     */
    public function reg2(Request $request)
    {
        $customTable = json_decode(Reg::currentConference()->option('reg_tables'))->regTable; //todo: table id
        if (!Auth::check())
        {
            if (is_object(User::where('email', $request->email)->first()))
            {
                //To-Do: error! 建议用 JS 判断
            }
            $user = new User;
            $user->name = $request->name;
            $user->email = $request->email; 
            $user->password = Hash::make($request->password2);
            $user->type = 'unregistered';
            $user->save();
        }
        else
            $user = Auth::user();
        $conf = $request->conference_id;
        $reg = Reg::current();
        $reg->user_id = $user->id;
        $reg->conference_id = $conf;
        $reg->type = $request->type;
        $reg->enabled = true;
        $reg->gender = $request->gender;
        //if (!empty($request->committee)) 
        $regInfo = new \stdClass();
        $personal_info = new \stdClass();
        $personal_info->dateofbirth = $request->dateofbirth; 
        $personal_info->province = $request->province; 
        $personal_info->school = $request->school; 
        $school = School::where('name', $request->school)->first();
        if (!empty($school)) $reg->school_id = $school->id;
        $personal_info->yearGraduate = $request->yearGraduate; 
        $personal_info->typeDocument = $request->typeDocument; 
        $personal_info->sfz = $request->sfz;         
        $personal_info->tel = $request->tel; 
        if (!empty($request->tel2))
            $personal_info->tel2 = $request->tel2; 
        if (!empty($request->qq))
            $personal_info->qq = $request->qq; 
        if (!empty($request->skype))
            $personal_info->skype = $request->skype; 
        if (!empty($request->wechat))
            $personal_info->wechat = $request->wechat;         
        $personal_info->parentname = $request->parentname; 
        $personal_info->parentrelation = $request->parentrelation; 
        $personal_info->parenttel = $request->parenttel;
        $regInfo->personinfo = $personal_info;
        if (isset($customTable->experience) && in_array($reg->type, $customTable->experience->uses))
        {
            $experience = new \stdClass();
            $experience->startYear = $request->startYear;
            $items = array();
            // TODO: 加载 MUNPANEL 收录会议的参会经历
            if (in_array($reg->type, $customTable->experience->custom))
            {
                if (!empty($request->level1) && !empty($request->date1) && !empty($request->name1) && !empty($request->role1))
                {
                    $expitem = new \stdClass();
                    $expitem->level = $request->level1;
                    $expitem->dates = $request->date1;
                    $expitem->name = $request->name1;
                    $expitem->role = $request->role1;
                    if (!empty($request->award1)) $expitem->award = $request->award1;
                    if (!empty($request->others1)) $expitem->others = $request->others1;
                    array_push($items, $expitem);
                }
                if (!(empty($request->level2) || empty($request->date2) || empty($request->name2) || empty($request->role2)))
                {
                    $expitem = new \stdClass();
                    $expitem->level = $request->level2;
                    $expitem->dates = $request->date2;
                    $expitem->name = $request->name2;
                    $expitem->role = $request->role2;
                    if (!empty($request->award2)) $expitem->award = $request->award2;
                    if (!empty($request->others2)) $expitem->others = $request->others2;
                    array_push($items, $expitem);
                }
                if (!(empty($request->level3) || empty($request->date3) || empty($request->name3) || empty($request->role3)))
                {
                    $expitem = new \stdClass();
                    $expitem->level = $request->level3;
                    $expitem->dates = $request->date3;
                    $expitem->name = $request->name3;
                    $expitem->role = $request->role3;
                    if (!empty($request->award3)) $expitem->award = $request->award3;
                    if (!empty($request->others3)) $expitem->others = $request->others3;
                    array_push($items, $expitem);
                }
            }
            $experience->item = $items;
            $regInfo->experience = $experience;
        }
        $conf_info = new \stdClass();
        foreach ($customTable->conference->items as $item)
        {
            if (isset($item->name) && !empty($request->{$item->name}))
                $conf_info->{$item->name} = $request->{$item->name};
            else
            {
                switch ($item->type)
                {
                    case 'preGroupOptions':
                        $conf_info->groupOption = $request->groupOption;
                    break;
                    case 'preRemarks': 
                        $conf_info->remarks = $request->remarks;
                    break;
                    case 'group':
                        foreach ($item->items as $subitem)
                            if (isset($subitem->name) && !empty($request->{$subitem->name}))
                                $conf_info->{$subitem->name} = $request->{$subitem->name};
                    break;
                }
            }
        }
        $targets = (array)$customTable->targets;
        foreach ($targets as $key => $item)
        {
            switch ($key)
            {
                case 'committee':
                if (!empty($request->{$targets['committee']}))
                    $conf_info->committee = $request->{$targets['committee']};
                break;
            }
        }
        $regInfo->conference = $conf_info;
        $reg->reginfo = json_encode($regInfo);
        $reg->save();
        foreach ($customTable->actions as $element)
        {
            if (empty($request->{$element->item})) continue;
            switch ($element->action)
            {
                case 'assignDelGroup':
                    if ($reg->type != 'delegate') break;
                    $dg_id = $request->{$element->item};
                    Delegategroup::find($dg_id)->delegates()->attach($reg->id);
                break;
            }
        }
        $reg->make();
        $reg->addEvent('registration_submitted', '');
        return redirect('/home');
    }

    /**
     * make a registration school verified.
     *
     * @param int $id the id of the registration
     * @return void
     */
    public function schoolVerify($id)
    {
        $school = Reg::current()->school;
        $specific =  User::find($id)->specific();
        if ($specific->school->id != $school->id)
            return "error";
        $specific->status = 'sVerified';
        $specific->save();
    }

    /**
     * make a registration school unverified.
     *
     * @param int $id the id of the registration
     * @return void
     */
    public function schoolUnverify($id)
    {
        $school = Reg::current()->school;
        $specific =  User::find($id)->specific();
        if ($specific->school->id != $school->id)
            return "error";
        $specific->status = 'reg';
        $specific->save();
    }

    /**
     * set a registration to a certain status.
     *
     * @param int $id the id of the registration
     * @param string $status the new status of the registration
     * @return void
     */
    public function setStatus($id, $status)
    {
        if (Reg::current()->type != 'ot' || (!Reg::current()->can('approve-regs')))
            return "Error";
        if ($status == 'paid' && (!Reg::current()->can('approve-regs-pay')))
            return "Error";
        $specific =  User::find($id)->specific();
        $specific->status = $status;
        $specific->save();
    }

    /**
     * Register school accounts from csv file.
     *
     * @return string registration result
     */
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

    /**
     * Register dais accounts from csv file.
     *
     * @return string registration result
     */
    public function regDais()
    {
         if (($handle = fopen("/var/www/munpanel/test.csv", "r")) !== FALSE) {
            $resp = "";
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $num = count($data);
                for ($c=0; $c < $num; $c++) {
                    $resp = $resp. $data[$c] . "<br />\n";
                }
                $user = User::firstOrNew(['email' => $data[1]]);
                $user->name = $data[0];
                $user->password = Hash::make(strtok($data[1], '@') . '2017');
                $resp = $resp. 'pwd: ' . strtok($data[1], '@') . '2017' ."<br/>\n";
                $user->email = $data[1];
                Delegate::destroy($user->id);
                Volunteer::destroy($user->id);
                Observer::destroy($user->id);
                $user->type = 'dais';
                $user->save();
                $dais = new Dais;
                $dais->user_id = $user->id;
                $dais->committee_id = Committee::where('name', '=', $data[2])->first()->id;
                $dais->position = 'dm';
                $dais->school_id = School::firstOrCreate(['name' => $data[3]])->id;
                $dais->save();
                $resp = $resp. response()->json($user) . "<br />\n";
                $resp = $resp. response()->json($dais) . "<br />\n";
            }
            fclose($handle);
            return $resp;
        }       
    }

    /**
     * Change the password of the logged in user.
     *
     * @param Request $request
     * @return Illuminate\Http\Response
     */
    public function doChangePwd(Request $request)
    {
        $user = Auth::user();
        if (Hash::check($request->oldPassword, $user->password))
        {
            $user->password = Hash::make($request->newPassword);
            $user->save();
            return redirect(mp_url('/home'));
        }
        else
            return view('error', ['msg' => 'Wrong password!']);
    }

    /**
     * Update a property of a user.
     *
     * @param Request $request
     * @param int $id the id of the user to be updated
     * @return void
     */
    public function updateUser(Request $request, $id)
    {
        if (Reg::current()->type != 'ot')
            return 'Error';
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

    /**
     * Update a property of a school.
     *
     * @param Request $request
     * @param int $id the id of the school to be updated
     * @return void
     */
    public function updateSchool(Request $request, $id)
    {
        if (Reg::current()->type != 'ot')
            return 'Error';
        $school = School::findOrFail($id);
        $name = $request->get('name');
        $value = $request->get('value');
        $school->$name = $value;
        $school->save();
    }

    /**
     * Update a property of a committee.
     *
     * @param Request $request
     * @param int $id the id of the committee to be updated
     * @return void
     */
    public function updateCommittee(Request $request, $id)
    {
        if (Reg::current()->type != 'ot')
            return 'Error';
        $committee = Committee::findOrFail($id);
        $name = $request->get('name');
        $value = $request->get('value');
        $committee->$name = $value;
        $committee->save();
    }

    /**
     * Delete a user from database.
     *
     * @param Request $request
     * @param int $id the id of the user to be deleted
     * @return void
     */
    public function deleteUser(Request $request, $id)
    {
        if (Reg::current()->type != 'ot')
            return 'Error';
        Reg::destroy($id);
    }

    /**
     * Delete a committee from database.
     *
     * @param Request $request
     * @param int $id the id of the committee to be deleted
     * @return void
     */
    public function deleteCommittee(Request $request, $id)
    {
        if (Reg::current()->type != 'ot')
            return 'Error';
        Committee::destroy($id);
    }

    /**
     * Delete a school from database.
     *
     * @param Request $request
     * @param int $id the id of the school to be deleted
     * @return void
     */
    public function deleteSchool(Request $request, $id)
    {
        if (Reg::current()->type != 'ot')
            return 'Error';
        School::destroy($id);
    }

    /**
     * Initiate permissions to the database.
     */
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

    /**
     * Pair roommates and partners according to name
     *
     * @return string paring result
     */
    public function autoAssign()
    {
        $regs = Reg::where('conference_id', Reg::current()->conference_id);
        $room = 0;
        $part = 0;
        $result1 = "";
        $result2 = "";
        foreach($regs as $reg)
        {
            if (!empty($reg->roommatename) && ($reg->status == 'oVerified' || $reg->status == 'paid'))
            {
                $result1 .= $reg->id ."&#09;". $reg->assignRoommateByName() . "<br>";
                $room++;
            }
            if ($reg->type == 'delegate')
            {
                if (isset($reg->delegate->partnername) && ($reg->delegate->status == 'oVerified' || $reg->delegate->status == 'paid'))
                {
                    $result2 .= $reg->id ."&#09;". $reg->delegate->assignPartnerByName() . "<br>";
                    $part++;
                }
            }
        }
        return "えるの室友配对遍历了$room" . "行记录<br>$result1<br>えるの搭档配对遍历了$part" . "行记录<br>$result2";
    }

    /**
     * A function to write some temporary code.
     */
    public function test()
    {
        dd(\App\Interviewer::list());
        if (($handle = fopen("/var/www/munpanel/test.csv", "r")) !== FALSE) {
            $resp = "";
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $user = User::where('name', $data[0])->get();
                $resp .= json_encode($user) . '<br>';
            }
            fclose($handle);
            return $resp;
        }

        $orders = Order::all();
        foreach ($orders as $order)
        {
            if ($order->status != 'paid')
                continue;
                $c = json_decode($order->content);
             foreach ($c as $row)
        {
            $id = $row->id;
            if (substr($id, 0, 4) == 'NID_')
                continue;
            $good = Good::find($id);
            if (is_object($good))
            {
                //if ($good->remains > 0) {
                    $good->remains -= $row->qty;
                    $good->save();
                //} else {
                 //   return view('error', ['msg' => '您的购物车中有商品已售空']);
                //}
            } else {
                //return view('error', ['msg' => '您的购物车中有商品已下架']);
            }
        }

        }
        return '...';
        $good = new Good;
        $good->name = 'BJMUN 徽章（小）';
        $good->image = 'storeitem/badge1.jpeg';
        $good->price = 5;
        $good->remains = 50;
        $good->save();
        $good = new Good;
        $good->name = 'BJMUN 徽章（大）';
        $good->image = 'storeitem/badge2.jpeg';
        $good->price = 7;
        $good->remains = 40;
        $good->save();
        $good = new Good;
        $good->name = 'BJMUN 书签';
        $good->image = 'storeitem/bookmark.jpeg';
        $good->price = 49;
        $good->remains = 10;
        $good->save();
        $good = new Good;
        $good->name = 'BJMUN 马克杯（陶瓷）';
        $good->image = 'storeitem/cup1.jpeg';
        $good->price = 25;
        $good->remains = 20;
        $good->save();
        $good = new Good;
        $good->name = 'BJMUN 马克杯（变色）';
        $good->image = 'storeitem/cup2.jpeg';
        $good->price = 45;
        $good->remains = 18;
        $good->save();
        $good = new Good;
        $good->name = 'BJMUN 帽衫（XXL）';
        $good->image = 'storeitem/hoot.jpeg';
        $good->price = 129;
        $good->remains = 8;
        $good->save();
        $good = new Good;
        $good->name = 'BJMUN 帽衫（L）';
        $good->image = 'storeitem/hoot.jpeg';
        $good->price = 129;
        $good->remains = 8;
        $good->save();
        $good = new Good;
        $good->name = 'BJMUN 钥匙链';
        $good->image = 'storeitem/key.jpeg';
        $good->price = 15;
        $good->remains = 50;
        $good->save();
        $good = new Good;
        $good->name = 'BJMUN U盘（8GB, USB2.0）';
        $good->image = 'storeitem/drive.jpeg';
        $good->price = 49;
        $good->remains = 45;
        $good->save();
        $good = new Good;
        $good->name = 'BJMUN 手机壳（iPhone 6/6S Plus）';
        $good->image = 'storeitem/phone.jpeg';
        $good->price = 29;
        $good->remains = 20;
        $good->save();
        return "gouli";
        $users = User::all();
        foreach($users as $user)
        {
            if ($user->type == 'delegate' && Delegate::find($user->id) == null)
            {
                $user->type = 'unregistered';
                $user->save();
            }
            if ($user->type == 'volunteer' && Delegate::find($user->id) == null)
            {
                $user->type = 'unregistered';
                $user->save();
            }
        }
        $assign = $this->autoAssign();
        return $assign;
        //$delgroup = new Delegategroup;
        //$delgroup->name = 'UNSC媒体';
        //$delgroup->display_name = 'UNSC媒体代表';
        //$delgroup->save();
        //$delgroup = Delegategroup::find(5);
        $delegates = Committee::find(2)->delegates;
        foreach ($delegates as $delegate)
        {
                $delegate->committee_id = 1;//$delgroup->delegates()->attach($delegate);
                $delegate->save();
        }
        /*$delgroup = new Delegategroup;
        $delgroup->name = 'UNSC国家';
        $delgroup->display_name = 'UNSC国家代表';
        $delgroup->save();
        $delegates = Committee::find(1)->delegates;
        foreach ($delegates as $delegate)
                $delgroup->delegates()->attach($delegate);*/
        return 'Aloha';
        /*$schools = School::all();
        foreach($schools as $school)
        {
            if ($school->user_id != 1)
            {
                $school->payment_method = 'group';
                $school->save();
            }
        }
        return 'ha';
        $delegates = Delegate::all();
        $i = 0;
        $result = "";
        foreach($delegates as $delegate)
        {
            if (isset($delegate->partnername))
            {
                $result .= "ID\t".$delegate->user->id ."\t". $delegate->assignPartnerByName() . "\n***";
                $i++;
            }
        }
        return "えるの搭档配对遍历了$i" . "行记录\n$result";*/
        $assign = $this->autoAssign();
        return $assign;
        $assignment = new Assignment;
        $assignment->subject_type = 'nation';
        $assignment->handin_type = 'upload';
        $assignment->title = 'ECOSOC 背景指导学术作业';
        $assignment->description = '<h4>题目</h4>1.请从工业化和基础设施建设中任选一角度，分析其对于城市发展的作用。<br>2.都市农业发展于20世纪上半叶，最初起源于日本与欧美等发达国家。日本的都市农业面积小且分散，但凭借其生产资料运输方便和经营者掌握高技术等优势在城市中具有强大的生命力。都市农业在城市中具有极为广泛的作用，例如保障城市居民的食品供应，改善周围的生态环境等。（下面a、b两问均需作答）<br>a.请简要叙述日本都市农业的形成原因（不多于500字）<br>b.请从可持续发展的角度分析日本都市农业的功能以及作用。（字数不限，请分条阐述）<br><br><h4>要求</h4>请每一对搭档共同完成一份学术作业，将两道题的作答写在一个.doc或.docx格式的文件中，于北京时间2017年1月21日晚23：59分前上传MUNPANEL系统。<br><br>本次会议学术作业均请各位代表独立完成，学术作业的全部内容需是撰写学术作业者自行完成的结果，请勿使撰写学术作业者之外的任何人对于学术作业参与包括但不限于：撰写、部分撰写、修改、点评等影响学术作业的行为，一经发现将被视为学术不端进行处理。<br><nt>在学术作业撰写时，鼓励各位代表进行各类资料的查阅。但主席团禁止任何形式的抄袭，若在学术作业的撰写过程中需要对于资料进行参考或引用，请在文中以脚注形式标注出引用文段，并在文后列举撰写过程中全部的参考资料。若对于学术资料进行引用但未标注，也会同样被认定为抄袭。<br><br>在本次会议中，被发现有任何学术不端行为的代表将被立即取消全部的评奖资格。<br><br>参考与引用标注方式如下：<br>书籍类：作者：《文献名》，出版社，出版年，页码。<br>论文与报刊类：作者：《文献名》，《刊物名》和期数。<br>外文类：作者，文献名（斜体），出版地：出版社或报刊名，时间，页码。<br>网络内容：文章主题，网络链接<br>其他形式的参考引用内容请自行注明';
        $assignment->deadline = '2017-01-21 23:59:59';
        $assignment->save();
        $committee = Committee::find(9);
        $committee->assignments()->attach($assignment);
        return 'aloha';
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
        return Reg::current()->delegate->nation->name;
        return Assignment::find(1)->belongsToDelegate(9);
        return response()->json(Reg::current()->delegate->assignments());
        return Auth::user()->invoiceAmount();
        return Auth::user()->invoiceItems();
        return "gou";
    }

    /**
     * Verify an email using a token.
     *
     * @param string $email the email to be verified
     * @param string $token the token to verify
     * @return string|Illuminate\Http\Response
     */
    public function doVerifyEmail($email, $token)
    {
        $user = User::where('email', $email)->firstOrFail();
        if ($user->emailVerificationToken == 'success' || $user->emailVerificationToken == $token)
        {
            $user->emailVerificationToken = 'success';
            $user->save();
            return redirect('/verifyTel');
        }
        return 'Token mismatch!';
    }

    /**
     * Send a verification code to a mobile using sms/call.
     * Then, display a modal for the user to input the code.
     *
     * @param Request $request
     * @param string $method 'sms' or 'call'
     * @param string $tel the number to be called/messaged
     * @return string|Illuminate\Http\Response
     */
    public function verifyTelModal(Request $request, $method, $tel)
    {
        $user = Auth::user();
        if ($user->telVerifications > 0)
            $user->telVerifications--;
        else
            return 'error';
        $user->tel = $tel;
        $user->save();
        $code = mt_rand(1000, 9999);
        $request->session()->flash('code', $code);
        if ($method == 'sms')
        {
            if (!$user->sendSMS('感谢您使用 MUNPANEL 系统。您的验证码为'.$code.'。'))
                return view('errorModal', ['msg' => '发送短信出错！请联系客服。对您造成的不便敬请谅解。']);
        }
        //SmsController::send([$tel], '尊敬的'.$user->name.'，感谢您使用 MUNPANEL 系统。您的验证码为'.$code.'。');
        else if ($method == 'call')
        {
            if(!SmsController::call($tel, $code))
                return view('errorModal', ['msg' => '拨打电话出错！抱歉我们暂不支持较多国家的电话服务，请尝试使用短信激活您的账户']);
        }
        else
            return view('errorModal', ['msg' => '您的尝试次数已用尽！']);
        return view('verifyTelModal');
    }

    /**
     * Verify a phone number.
     *
     * @param Request $request
     * @return string|Illuminate\Http\Response
     */
    public function doVerifyTel(Request $request)
    {
        $correct = $request->session()->get('code');
        $code = $request->code;
        if (isset($correct) && $correct == $code)
        {
            $user = Auth::user();
            $user->telVerifications = -1;
            $user->save();
            return redirect('/home');
        } else {
            return redirect('/verifyTel'); //To-Do: error prompt
        }
    }

    /**
     * Resend verification mail to the logged in user.
     *
     * @return Illuminate\Http\Response
     */
    public function resendRegMail()
    {
        $user = Auth::user();
        $user->sendVerificationEmail();
        return redirect('/verifyEmail');
    }

    /**
     *
     */
    public function doSwitchIdentity(Request $request)
    {
        if ($request->reg == 'logout') {
            Auth::logout();
            return redirect('/login');
        }
        $reg = Reg::findOrFail($request->reg);
        if ($reg->user_id != Auth::user()->id)
            return 'error';
        $reg->login(true);
        return redirect('/home');
    }
}
