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
use App\Committee;
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

    public function emailLogo(Request $request)
    {
        //dd($request->getQueryString()); //TO-DO: mark email as read
        return redirect(cdn_url('/email/munpanel.png'));
    }

    public function resend($id)
    {
        $email = Email::findOrFail($id);
        $email->send();
    }

    public function sendDaisResult()
    {
        if (($handle = fopen("/var/www/munpanel/app/test.csv", "r")) !== FALSE) {
            $resp = "";
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $user = Dais::find($data[0]);
                if (!is_object($user))
                    continue;
                if ($user->status == 'success')
                    continue;
                $user = $user->user();
                $resp = $resp. $user->name . "<br />\n";
                $resp = $resp. $data[1] . "<br />\n";
                $mail = new Email;
                $mail->id = generateID();
                $mail->conference_id = 3;
                $mail->title = 'BJMUNSS 2017 学术团队申请结果';
                $mail->setReceiver($user);
                $mail->sender = 'BJMUN';
                $mail->content = '首先感谢您对北京高中生模拟联合国协会的支持，以及您在学术团队招募工作中的积极参与。<br/><br/>在您的申请中，我们看到了您的参与热情以及过人的能力。我们为您的关注感到荣幸和感谢。然而，考虑到包括内场设置在内的众多方面因素，我们在这里很遗憾地通知您，您可能无法参加到BJMUNSS 2017学术团队中。这并不是对您学术能力的否定，只是我们由于各种条件限制做出的决定，希望您能够理解并配合我们的工作。<br/><br/>暂时的分离并不意味着永别，北京高中生模拟联合国协会永远欢迎您的参与，愿您在今后的模联道路一帆风顺，愿北京模联一直是我们共同的家。<br/><br/>北京市高中生模拟联合国协会';
                $mail->send();
                $mail->save();
                sleep(5);
            }
            fclose($handle);
            return $resp;
        }
        return "gou";
        if (($handle = fopen("/var/www/munpanel/app/test.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $user = Reg::find($data[0]);
                if (!is_object($user))
                    continue;
                $user = $user->dais;
                $msg = "恭喜！我们很荣幸的通知您，您已经通过面试并正式被北京市高中生模拟联合国协会录取为 2017 年北京市高中生模拟联合国暑期研讨会（BJMUNSS 2017）".$user->committee->display_name." 主席团成员。<br/><br/>这意味着，在未来的 3-4 个月里，您将与其他 35 位学术团队成员和 13 位组织团队成员共同工作，筹备在不久之后召开的会议。<br/><br/>在此请允许我为您介绍一些有关您未来工作的基本情况：<br/>1. 本次会议共设置 5 个委员会，三中两英，中文每个委员会配置 6 位主席团成员以及 2 位主席团负责人、英文每个委员会配置 5 位主席团成员以及 1 位主席团负责人，共 36 人；学术总监中英委员会各 2 位，共 4 人。<br/><br/>2. 我们已经为您设立了Console Mail - BJMUN工作邮箱，通过该邮箱您可以与其他BJMUN工作人员联系，相关信息如下：<br/><br/>登陆地址： http://mail.bjmun.org<br/>邮箱地址：".$data[2]."<br/>初始密码：".$data[1]."<br/>如果您需要以BJMUM委员会或BJMUN学术团队成员的身份联系外界，请务必使用工作邮箱而不是您的个人邮箱。<br/>如果您无法登陆您的邮箱或忘记邮箱密码，请微信联系：adamyi<br/><br/>3. 请确保您的 MUNPANEL 账号（为您之前申请学术团队时所使用的账号）可以正常登陆，之后的席位分配、学术文件下发、学术作业收集等功能均将在 MUNPANEL 系统上进行。如您忘记密码或无法登陆，请首先尝试系统登录页中的找回密码功能，如该功能仍然无法帮助您重新登录您的账号，请微信联系 adamyi。所有的主席信息均以您之前申请学术团队时所使用的账号，尽管您可以使用您的邮箱用户名和邮箱密码以 Console Mail 方式登录 MUNPANEL 系统，但这样登陆将会新建一个您的工作邮箱的无任何权限的 MUNPANEL 账号。因此，目前请不要尝试以 Console Mail 方式登录 MUNPANEL 系统。日后我们可能会将您的 Console Mail 工作邮箱绑定至您的 MUNPANEL 账号，届时将可以以 Console Mail 方式登录 MUNPANEL 系统，会有另行通知。目前，请仅以 MUNPANEL 账号方式登录 MUNPANEL 系统。<br/><br/>4. 请扫描下方二维码加入 BJMUNSS 2017 学术团队 微信群，加群后请修改群名片为您的真实姓名。（二维码若过期过期请微信联系：ZQH1060787929）。<br/><img src='https://dev.yiad.am/bjss17atWXQR.jpg' width='100%'/></img><br/>5. 本次学术团队通讯录已经提供在本封邮件附件中，供您下载使用。<br/>6. 您若在以后单独建立了委员会主席小群（通常为主席团负责人拉群），请邀请秘书长与相应会议语言的两位学术总监加入群聊。<br/><br/>最后，再次欢迎您加入北京模联这个大家庭，如果您对于学术团队工作还有任何问题，请随时与official@bjmun.org联系。<br/><br/>北京市高中生模拟联合国协会";
                $mail = new Email;
                $mail->id = generateID();
                $mail->conference_id = 3;
                $mail->title = 'BJMUNSS 2017 学术团队录取信';
                $mail->setReceiver($user->user());
                $mail->sender = 'BJMUN';
                $mail->content = $msg;
                $mail->send();
                $mail->save();
                $user->user()->sendSMS('恭喜！我们很荣幸的通知您，您已经通过面试并正式被录取为BJMUNSS 2017 '.$user->committee->display_name.' 主席团成员。具体请见邮件通知，或访问'.mp_url('/showEmail/'.$mail->id).' ，感谢。');
                sleep(5);
            }
            fclose($handle);
            return $resp;
        }
        return "aaa";
        $regs = Dais::where('conference_id', 3)->where('status', 'oVerified')->get();
        foreach ($regs as $reg)
        {
            $reg->user()->sendSMS('感谢您申请成为BJMUNSS 2017学术团队一员。在您的申请中，我们看到了您的参与热情以及过人的能力。然而，考虑到包括会场设计在内的多方面因素，我们很遗憾地通知您，您可能无法参加到BJMUNSS 2017学术团队中。暂时的分离并不意味着永别，北京高中生模拟联合国协会永远欢迎您的参与，愿您在今后的模联道路一帆风顺，愿北京模联一直是我们共同的家。我们永远在一起。');
            $reg->status = 'fail';
            $reg->save();
        }
        return "bbb";
        if (($handle = fopen("/var/www/munpanel/app/test.csv", "r")) !== FALSE) {
            $resp = "";
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $user = Reg::find($data[0]);
                if (!is_object($user))
                    continue;
                $user=$user->dais;
                $user->status='success';
                $user->committee_id = Committee::where('name', $data[1])->first()->id;
                $user->save();
            }
            fclose($handle);
            return $resp;
        }
        return "aaa";
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
