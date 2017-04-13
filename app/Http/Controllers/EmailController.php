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

use App\Email;
use App\User;
use App\Reg;
use App\Dais;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /*
        $mail = new Email;
        $mail->id = generateID();
        $mail->conference_id = 2; //ToDo
        $mail->title = 'MUNPANEL 账号验证';
        $mail->setReceiver($this);
        $mail->sender = 'MUNPANEL Team';
        $mail->content = '感谢您使用 MUNPANEL 系统！请点击以下链接验证您的电子邮箱：<br/><pre><a href="'.$url.'">'.$url.'</a></pre>';
        $mail->send();
        $mail->save();
         */
    }

    public function showEmail($id)
    {
        $email = Email::findOrFail($id);
        return view('emailTemplate', [
            'id' => $email->id,
            'title' => $email->title,
            'content' => $email->content,
            'sender' => $email->sender,
            'receiver' => json_decode($email->receiver),
            'webView' => true,
        ]);

    }

    public function resend($id)
    {
        $email = Email::findOrFail($id);
        $email->send();
    }

    public function sendDaisResult()
    {
        $resp = "";
        $regs = Dais::where('conference_id', 3)->where('status', 'fail')->get();
        foreach ($regs as $reg)
        {
            $user = $reg->user();
            $resp = $resp. $user->name . "<br />\n";
            $mail = new Email;
            $mail->id = generateID();
            $mail->conference_id = 3;
            $mail->title = 'BJMUNSS 2017 学术团队申请 第一轮结果';
            $mail->setReceiver($user);
            $mail->sender = 'BJMUN';
            $mail->content = '感谢您的申请，我们看到了您认真的态度和出色的能力，遗憾的是，您未通过BJMUNSS 2017学术团队第一轮申请审核。这是核心学术团队经过多方考量而最终做出的决定，这并不表明您的能力不足。希望您不要因为本次学术团队的录取结果而丧失对模拟联合国活动的热情，在此我们仅希望您能够以最饱满的精神迎接本次会议后其他各个模拟联合国活动，并且致以我们最诚挚的歉意。<br/><br/>祝好，<br/>北京市高中生模拟联合国协会';
            $mail->send();
            $mail->save();
            $user->sendSMS("感谢您申请成为BJMUNSS 2017学术团队成员，然而遗憾的是您未能通过第一轮申请审核。这是我们经多方考量而最终做出的决定，并不表明您的能力不足。再次感谢您对BJMUN的关注与支持，愿北京模联一直是我们共同的家。");
        }
        return $resp;
        if (($handle = fopen("/var/www/munpanel/app/test.csv", "r")) !== FALSE) {
            $resp = "";
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $user = Reg::find($data[0]);
                if (!is_object($user))
                    continue;
                $user = $user->user;
                $resp = $resp. $user->name . "<br />\n";
                $resp = $resp. $data[1] . "<br />\n";
                $mail = new Email;
                $mail->id = generateID();
                $mail->conference_id = 3;
                $mail->title = 'BJMUNSS 2017 学术团队申请 第一轮结果';
                $mail->setReceiver($user);
                $mail->sender = 'BJMUN';
                $mail->content = '感谢您的申请，在此恭喜您通过BJMUNSS 2017学术团队第一轮申请审核，获得参加复选的资格。<br/><br/>为了更加全面地了解您的能力，保证选拔的公正高效，组织团队决定于2017年4月15日（本周六）上午于中国人民大学附属中学进行复选面试。<br/><br/>请您于当日'.$data[1].'于人大附中高中楼七层第九会议室参加面试，参加面试及等候时间总时长预计为1-2小时。您如有特殊原因不能参加面试，请务必来信说明。面试当天请您准时到达现场，若不能按时到达请提前通知我们。<br/><br/>另，请打印两份申请文档随身携带。感谢您的配合！<br/><br/>请您收到后发送一封确认邮件至<a href="mailto:official@bjmun.org" _src="mailto:official@bjmun.org">official@bjmun.org</a>。如您同时也收到了一封拒信，则您可能在系统上提交了多份申请，以本封面试通知邮件为准。<br/><br/>面试时请从东门进入，如找不到面试地点请及时联系秘书长朱淇惠18600265900。<br/><br/>祝好，<br/>北京市高中生模拟联合国协会';
                $mail->send();
                $mail->save();
                $user->sendSMS("感谢您申请成为BJMUNSS 2017学术团队成员，您已通过第一轮审核。请于本周六".$data[1]."前往人大附中高中楼七层第九会议室参加面试。具体参见邮件通知，收到后烦请发送一封确认邮件至official@bjmun.org，感谢。");
            }
            fclose($handle);
            return $resp;
        }
        /*$mail = new Email;
        $mail->id = generateID();
        $mail->conference_id = 3;
        $mail->title = 'MUNPANEL 账号验证';
        $mail->setReceiver();
        $mail->sender = 'MUNPANEL Team';
        $mail->content = '感谢您使用 MUNPANEL 系统！请点击以下链接验证您的电子邮箱：<br/><pre><a href="'.$url.'">'.$url.'</a></pre>';
        $mail->send();
        $mail->save();*/
        return "hi";
    }
}
