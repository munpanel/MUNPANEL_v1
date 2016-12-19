<?php

namespace App\Http\Controllers;

use Config;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayController extends Controller
{
    public function payInfo(Request $request)
    {
        $srv = new TeegonService(Config::get('teegon.api_url'));
        $param['order_no'] = Config::get('teegon.oderid_prefix').Auth::user()->id;//.substr(md5(time().print_r($_SERVER,1)), 0, 8); //订单号
        $param['channel'] = $request->channel;
        $param['return_url'] = $request->return;
        $param['amount'] = Auth::user()->invoiceAmount();
        $param['subject'] = 'BJMUNC 2017会费';
        $param['metadata'] = json_encode(array('uid'=> Auth::user()->id));
        $param['notify_url'] = secure_url('/api/payNotify');//支付成功后天工支付网关通知
        $param['wx_openid'] = "";//$_POST['wx_openid'];//没有给空
        //return $param;
        return $srv->pay($param,false);
 
    }
    public function payNotify(Request $request)
    {
        //TO UPDATE: verification for security purposes (Teegon is still working on it. Update after they update lol). Payment status is strongly recommended to be manually checked now.
        file_put_contents("/var/www/munpanel/storage/t", 'a'.var_export($request,true));
        $amount = $request->amount;
        $meta = json_decode($request->metadata);
        $user = User::find($meta->uid);
        //VERIFY AMOUNT
        if ($user->invoiceAmount() != $amount)
            return "ERROR";
        //END VERIFICATION
        $specific = $user->specific();
        $specific->status = 'paid';
        $specific->save();
    }
}
