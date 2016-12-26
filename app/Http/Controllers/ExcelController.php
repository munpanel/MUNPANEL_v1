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
use App\Nation;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class ExcelController extends Controller
{
    public function exportRegistrations($flag = 'normal', $ext = 'xlsx') {
        if (Auth::user()->type != 'ot')
            return view('error', ['msg' => '您不是组织团队成员，无权进行该操作！']);
        //TO-DO: permission check
        Excel::create('registrations', function($excel) {
            $excel->sheet('registrations', function($sheet){
                $sheet->appendRow(array('UID(勿改 新添留空)', '姓名', 'E-mail', '密码(不改留空)', '委员会/志愿者/观察员', '席位', '学校', '年级', '身份证号', 'QQ', '微信', '搭档姓名', '室友姓名', '电话', '家长电话', '性别', '住宿', '状态'));
                if (Route::current()->getParameter('flag') == 'empty')
                    return;
                $delegates = Delegate::with('user', 'committee', 'school', 'nation')->get();
                $count = $delegates->count();
                foreach ($delegates as $del)
                {
                    switch ($del->grade)
                    {
                        case 1: $grade = '小学及以下'; break;
                        case 2: $grade = '初一'; break;
                        case 3: $grade = '初二'; break;
                        case 4: $grade = '初三'; break;
                        case 5: $grade = '高一'; break;
                        case 6: $grade = '高二'; break;
                        case 7: $grade = '高三'; break;
                        case 8: $grade = '本科及以上'; break;
                        default: $grade = 'error';
                    }
                    switch ($del->status)
                    {
                        case 'reg': $status = '等待学校审核'; break;
                        case 'sVerified': $status = '等待组委审核'; break;
                        case 'oVerified': $status = '待缴费'; break;
                        case 'paid': $status = '成功'; break;
                    }
                    if (is_null($del->nation))
                        $nation = '未分配';
                    else
                        $nation = $del->nation->name;
                    if ($del->accomodate)
                        $accomodate = '是';
                    else
                        $accomodate = '否';
                    if ($del->gender == 'male')
                        $gender = '男';
                    else
                        $gender = '女';
                    $sheet->appendRow(array($del->user_id, $del->user->name, $del->user->email, '', $del->committee->name, $nation, $del->school->name, $grade, $del->sfz . "\t", $del->qq, $del->wechat, $del->partnername, $del->roommatename, $del->tel, $del->parenttel, $gender, $accomodate, $status));
                }
                $volunteers = Volunteer::with('user', 'school')->get();
                $count += $volunteers->count();
                foreach ($volunteers as $vol)
                {
                    switch ($vol->grade)
                    {
                        case 1: $grade = '小学及以下'; break;
                        case 2: $grade = '初一'; break;
                        case 3: $grade = '初二'; break;
                        case 4: $grade = '初三'; break;
                        case 5: $grade = '高一'; break;
                        case 6: $grade = '高二'; break;
                        case 7: $grade = '高三'; break;
                        case 8: $grade = '本科及以上'; break;
                        default: $grade = 'error';
                    }
                    switch ($vol->status)
                    {
                        case 'reg': $status = '等待学校审核'; break;
                        case 'sVerified': $status = '等待组委审核'; break;
                        case 'oVerified': $status = '待缴费'; break;
                        case 'paid': $status = '成功'; break;
                    }
                    if (is_null($vol->nation))
                        $nation = '未分配';
                    else
                        $natioin = $vol->nation->name;
                    if ($vol->accomodate)
                        $accomodate = '是';
                    else
                        $accomodate = '否';
                    if ($vol->gender == 'male')
                        $gender = '男';
                    else
                        $gender = '女';
                    $sheet->appendRow(array($vol->user_id, $vol->user->name, $vol->user->email, '', '志愿者', '无', $vol->school->name, $grade, $vol->sfz . "\t", $vol->qq, $vol->wechat, '', $vol->roommatename, $vol->tel, $vol->parenttel, $gender, $accomodate, $status));
                }
                //TO-DO: observers
                $sheet->setColumnFormat(array(
                    'I' => '@',
                    'J' => '@',
                    'K' => '@',
                    'N' => '@',
                    'O' => '@'
                ));
            });
        })->export($ext);
    }

    private static function mapData($valuerow, $key, $valuekey)
    {
        if (isset($valuerow[$valuekey]))
            return $valuerow[$valuekey];
        if (isset($key))
            return $key;
        return "";
    }

    public function importRegistrations(Request $request)
    {
        if ($request->hasFile('file') && $request->file('file')->isValid())
        {
            $file = storage_path('/app/'.$request->file->store('importBackups'));
            Excel::selectSheets('registrations')->load($file, function($reader) {
                $result = $reader->toArray();
                foreach ($result as $row)
                {
                    foreach ($row as &$cell)
                        if (isset($cell))
                                $cell = trim($cell);
                    if (isset($row['uid勿改_新添留空']))
                        $user = User::firstOrNew(['id' => $row['uid勿改_新添留空']]);
                    else if(isset($row['e_mail']))
                        $user = User::firstOrNew(['email' => $row['e_mail']]);
                    else
                        continue;
                    $user->name = ExcelController::mapData($row, $user->name, '姓名');
                    $user->email = ExcelController::mapData($row, $user->email, 'e_mail');
                    if (isset($row['密码不改留空']))
                        $user->password = Hash::make($row['密码不改留空']);
                    if (!isset($row['委员会志愿者观察员']))
                    {
                        $user->save(); // no change of other data; if new user, default status is unregistered
                        continue;
                    }
                    if ($row['委员会志愿者观察员'] == '志愿者') 
                    {
                        $user->type = 'volunteer';
                        $user->save();
                        $vol = Volunteer::firstOrNew(['user_id' => $user->id]);
                        $vol->user_id = $row['uid勿改_新添留空'];
                        if (isset($row['学校']))
                            $vol->school_id = School::firstOrCreate(['name' => $row['学校']])->id; // the default uid of school is 1, so a new school is a non-member school by default
                        if (isset($row['年级']))
                        {
                            switch ($row['年级'])
                            {
                                case '小学及以下': $grade = 1; break;
                                case '初一': $grade = 2; break;
                                case '初二': $grade = 3; break;
                                case '初三': $grade = 4; break;
                                case '高一': $grade = 5; break;
                                case '高二': $grade = 6; break;
                                case '高三': $grade = 7; break;
                                case '本科及以上': $grade = 8; break;
                                default: $grade = $vol->grade;
                            }
                            $vol->grade = $grade;
                        }
                        if (isset($row['状态']))
                        {
                            switch ($row['状态'])
                            {
                                case '等待学校审核': $status = 'reg'; break;
                                case '等待组委审核': $status = 'sVerified'; break;
                                case '待缴费': $status = 'oVerified'; break;
                                case '成功': $status = 'paid'; break;
                                default: $status = $vol->status;
                            }
                            $vol->status = $status;
                        }
                        $sfz1 = $row['身份证号'];
                        if (substr($sfz1, -1) == "\t") $sfz1 = rtrim($sfz1, "\t");
                        $vol->sfz = $sfz1;
                        $vol->qq = ExcelController::mapData($row, $vol->qq, 'qq');
                        $vol->wechat = ExcelController::mapData($row, $vol->wechat, '微信');
                        $vol->roommatename = ExcelController::mapData($row, $vol->roommatename, '室友姓名');
                        $vol->tel = ExcelController::mapData($row, $vol->tel, '电话');
                        $vol->parenttel = ExcelController::mapData($row, $vol->parenttel, '家长电话');
                        if (isset($row['性别']))
                        {
                            if ($row['性别'] == '女')
                                $vol->gender = 'female';
                            else
                                $vol->gender = 'male';
                            }
                        if (isset($row['住宿']))
                        {
                            if ($row['住宿'] == '是')
                                $vol->accomodate = true;
                            else
                                $vol->accomodate = false;
                        }
                        $vol->save();
                        Delegate::destroy($user->id);
                        Observer::destroy($user->id);
                    }
                    else if ($row['委员会志愿者观察员'] == '观察员')
                    {
                        //To-Do
                    }
                    else //in this case, it's delegate
                    {
                        $user->type = 'delegate';
                        $user->save();
                        $del = Delegate::firstOrNew(['user_id' => $user->id]);
                        if (isset($row['学校']))
                            $del->school_id = School::firstOrCreate(['name' => $row['学校']])->id; // the default uid of school is 1, so a new school is a non-member school by default
                        $del->committee_id = Committee::firstOrCreate(['name' => $row['委员会志愿者观察员']])->id;
                        if (isset($row['席位']))
                        {
                            if ($row['席位'] != '未分配')
                                $del->nation_id = Nation::firstOrCreate(['name' => $row['席位']])->id;
                            else
                                $del->nation_id = null;
                        }
                        if (isset($row['年级']))
                        {
                            switch ($row['年级'])
                            {
                                case '小学及以下': $grade = 1; break;
                                case '初一': $grade = 2; break;
                                case '初二': $grade = 3; break;
                                case '初三': $grade = 4; break;
                                case '高一': $grade = 5; break;
                                case '高二': $grade = 6; break;
                                case '高三': $grade = 7; break;
                                case '本科及以上': $grade = 8; break;
                                default: $grade = $vol->grade;
                            }
                            $del->grade = $grade;
                        }
                        if (isset($row['状态']))
                        {
                            switch ($row['状态'])
                            {
                                case '等待学校审核': $status = 'reg'; break;
                                case '等待组委审核': $status = 'sVerified'; break;
                                case '待缴费': $status = 'oVerified'; break;
                                case '成功': $status = 'paid'; break;
                                default: $status = $del->status;
                            }
                            $del->status = $status;
                        }
                        $del->email = $user->email;
                        $sfz1 = $row['身份证号'];
                        if (substr($sfz1, -1) == "\t") $sfz1 = rtrim($sfz1, "\t");
                        $del->sfz = $sfz1;
                        $del->qq = ExcelController::mapData($row, $del->qq, 'qq');
                        $del->wechat = ExcelController::mapData($row, $del->wechat, '微信');
                        $del->partnername = ExcelController::mapData($row, $del->partnername, '搭档姓名');
                        $del->roommatename = ExcelController::mapData($row, $del->roommatename, '室友姓名');
                        $del->tel = ExcelController::mapData($row, $del->tel, '电话');
                        $del->parenttel = ExcelController::mapData($row, $del->parenttel, '家长电话');
                        if (isset($row['性别']))
                        {
                            if ($row['性别'] == '女')
                                $del->gender = 'female';
                            else
                                $del->gender = 'male';
                            }
                        if (isset($row['住宿']))
                        {
                            if ($row['住宿'] == '是')
                                $del->accomodate = true;
                            else
                                $del->accomodate = false;
                        }
                        $del->save();
                        Volunteer::destroy($user->id);
                        Observer::destroy($user->id);
                    }
                }
            });
            return redirect(secure_url('/regManage'));
        }
        else
            return view('error', ['msg' => '操作失败！']);
    }
}
