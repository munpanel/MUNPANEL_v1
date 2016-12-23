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
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;


class ExcelController extends Controller
{
    public function exportRegistrations($ext = 'xlsx') {
        if (Auth::user()->type != 'ot')
            return 'Error';
        //TO-DO: permission check
        Excel::create('registrations', function($excel) {
            $excel->sheet('registrations', function($sheet){
                $sheet->appendRow(array('UID(勿改 新添留空)', '姓名', 'E-mail', '密码(不改留空)', '委员会/志愿者/观察员', '席位', '学校', '年级', '身份证号', 'QQ', '微信', '搭档姓名', '室友姓名', '电话', '家长电话', '性别', '住宿'));
                $delegates = Delegate::with('user', 'committee', 'school', 'nation')->get();
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
                    $sheet->appendRow(array($del->user_id, $del->user->name, $del->user->email, '', $del->committee->name, $nation, $del->school->name, $grade, $del->sfz, $del->qq, $del->wechat, $del->partnername, $del->roommatename, $del->tel, $del->parenttel, $gender, $accomodate));
                }
                $volunteers = Volunteer::with('user', 'school')->get();
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
                    $sheet->appendRow(array($vol->user_id, $vol->user->name, $vol->user->email, '', '志愿者', '无', $vol->school->name, $grade, $vol->sfz, $vol->qq, $vol->wechat, $vol->partnername, $vol->roommatename, $vol->tel, $vol->parenttel, $gender, $accomodate));
                }
                //TO-DO: volunteers
            });
        })->export($ext);
    }

    public function importRegistrations() {

    }
}
