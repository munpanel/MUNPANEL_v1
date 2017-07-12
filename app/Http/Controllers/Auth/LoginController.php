<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * Developed by Adam Yi <xuan@yiad.am>
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use App\Reg;
use Config;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout', 'logoutReg']]);
    }


    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $previous = session('_previous.url');

        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        session(['url.intended' => $previous]);

        return redirect(route('login'));
    }

    /**
     * Log the reg out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logoutReg()
    {
        session()->forget('regIdforConference'.Reg::currentConferenceID());
        session()->forget('regIdforConference'.Reg::currentConferenceID().'sudo');
        return redirect('/home');
    }

    public function loginConsoleMail()
    {
        return view('auth.login', ['mailLogin' => true]);
    }

    public function doLoginMail(Request $request)
    {
        try {
            if(@imap_open( Config::get('munpanel.imap_host') , $request->email , $request->password ))
            {
                $user = User::where('email', $request->email)->first();
                if (is_object($user))
                {
                    Auth::login($user);
                    $regs = DB::table('defaultregs')->where('email', $request->email)->get(['reg_id']);
                    foreach ($regs as $reg0)
                    {
                        $reg = Reg::find($reg0->reg_id);
                        if (is_object($reg))
                        {
                            $reg->user_id = $user->id;
                            $reg->save();
                        }
                    }
                    DB::table('defaultregs')->where('email', $request->email)->delete();
                    return redirect()->intended('/home');
                }
                $email = explode('@', $request->email);
                $password = $request->password;
                $user_name = $email[0];
                $domain_name = $email[1];
                $auth_timestamp = time();                                // 定义当前时间戳
                $auth_type = 'auth';
                // 申请feed token， 需要向URL发送一个content-type 为 application/x-www-form-urlencoded 的HTTP POST请求，此请求必须如下postdata中的三个参数：
                $ch = curl_init(Config::get('munpanel.mailapi_host') . 'api/service/auth/get_token');
                $postdata = array(
                        'auth_key'         =>  Config::get('munpanel.mailapi_key'),
                        'auth_timestamp'  =>  $auth_timestamp,
                        'auth_signature'   =>   md5(Config::get('munpanel.mailapi_secret') . Config::get('munpanel.mailapi_key') . $auth_timestamp),
                        );
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata));
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                $auth_token = explode("string", $result)[0];

                //return $auth_token;

                // feed API认证
                // Feed API传递认证信息是把认证信息参数放到HTTP的Authorization头中传递。认证信息参数包含auth_type, auth_key, auth_timestamp, auth_token, auth_signature.  其中auth_signature签名的算法为：MD5(API_SECRET + auth_key + auth_timestamp + auth_token) 。
                $auth_signature = md5(Config::get('munpanel.mailapi_secret') . Config::get('munpanel.mailapi_key')  . $auth_timestamp . $auth_token); // 获取签名
                $auth_info = array(
                        'auth_key'       => Config::get('munpanel.mailapi_key'),                      // API_KEY
                        'auth_timestamp' => $auth_timestamp,            // 系统当前的整数时间戳
                        'auth_token'     => $auth_token,                 // 会话Token
                        'auth_signature' => $auth_signature,              // 签名
                        );
                $tmp = array();
                foreach($auth_info as $k=>$v){
                    $tmp[] = $k . '="' . rawurlencode($v) . '"';               // 注意：由于是通过HTTP头方式传递认证信息参数，所以所有的参数的值都必须要进行RawUrlEncode处理。
                }
                $authorization = $auth_type . ' ' . implode(', ', $tmp);        // 得到Feed API认证
                /*
                例如$authorization的输出结果为：
                 auth auth_key="zyy%40zyy.com",
                 auth_timestamp="1262307600",
                 auth_token="nq54aHpZseNWPwxwfrklZO8uGSU%3D",
                 auth_signature="3e7f0e9a79c51f1a67d74ac99fad08a3"
                */
                // }}}
                //
                //return $authorization;
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, Config::get('munpanel.mailapi_host') . 'api/admin/domain/' . $domain_name . '/user/' . $user_name);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: $authorization",                      // 在http头部传递认证信息
                "Content-Typ: application/atom+xml;type=entry",  // 获取资源详情类型
                ));
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $result = curl_exec($ch);
                // }}}
                //
                preg_match('/<content>(.*)<\\/content>/', $result, $matches);
                $user = User::create([
                'name' => $matches[1],
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'emailVerificationToken' => 'success',
                'google2fa_enabled' => false
                //'type' => 'unregistered'
                ]);
                Auth::login($user);
                $regs = DB::table('defaultregs')->where('email', $request->email)->get(['reg_id']);
                foreach ($regs as $reg0)
                {
                    $reg = Reg::findOrFail($reg0->reg_id);
                    $reg->user_id = $user->id;
                    $reg->save();
                }
                DB::table('defaultregs')->where('email', $request->email)->delete();
                return redirect(route('verifyTel'));
            }
            else
            {
                return 'error';
            }
        } catch (Exception $e) {
            return 'error';
        }
    }
}
