<?php
namespace App\Http\Controllers;
use Config;
use Illuminate\Http\Requests;
class TeegonService extends Controller{

    public $base_url;
    public $id;

    function __construct($base_url){
        $this->base_url = $base_url;
    }

    function pay($param,$result_decode = true){
        if(empty($param['out_order_no'])){
            return "订单号错误";
        }

        if(empty($param['return_url'])){
            return "付款成功回调地址错误";
        }

        if(empty($param['amount'])){
            return "支付金额错误";
        }

        if(empty($param['amount'])){
            return "支付金额错误";
        }

        $param['client_id'] = Config::get('teegon.client_id');
        $param['client_secret'] = Config::get('teegon.client_secret');
         $rst = $this->post('v1/charge/', $param);
        if($result_decode == true){
            return json_decode($rst);
        }

        return $rst;
    }

    function verify_return(){
        if($_REQUEST['charge_id']){
            if(empty($_REQUEST['sign'])){
                return false; //return array('status'=>"1",'error_msg'=>'<h1>天工服务端返回签名信息错误!</h1><hr ><pre>','param'=>$_REQUEST);
            }

            if(!$this->get_sign_veryfy($_REQUEST,$_REQUEST['sign'])){
                return false; //return array('status'=>"2",'error_msg'=>'<h1>签名验证错误请检查签名算法!</h1><hr ><pre>','param'=>$_REQUEST);
            }

            return true;
            return array('status'=>"0",'error_msg'=>'','param'=>$_REQUEST);
        }
    }

    function post($path, $params=array()){
        return $this->call('post', $path, $params);
    }

    function get($path, $params=array()){
        return $this->call('get', $path, $params);
    }

    function delete($path, $params=array()){
        return $this->call('delete', $path, $params);
    }

    function put($path, $params=array()){
        return $this->call('put', $path, $params);
    }

    function call($method, $path, $params=array()){
        $url = $this->base_url.$path;
        $options = array(
            CURLOPT_HEADER => 0,
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 10,
        );

        $param_string = http_build_query($params);
        switch(strtolower($method)){
            case 'post':
                $options += array(CURLOPT_POST => 1,
                              CURLOPT_POSTFIELDS => $param_string);
                break;
            case 'put':
                $options += array(CURLOPT_PUT => 1,
                              CURLOPT_POSTFIELDS => $param_string);
                break;
            case 'delete':
                $options[CURLOPT_CUSTOMREQUEST] = 'DELETE';
                if($param_string)
                    $options[CURLOPT_URL] .= '?'.$param_string;
                break;
            default:
                if($param_string)
                    $options[CURLOPT_URL] .= '?'.$param_string;
        }

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        if( ! $result = curl_exec($ch))
        {
            //dd(curl_error($ch));
            // Error Number 77 -> check SSL. Just as a remind
            $this->on_error(curl_error($ch));
        }
        curl_close($ch);
        return $result;
    }

    public function get_sign_veryfy($para_temp, $sign){
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->para_filter($para_temp);

        //对待签名参数数组排序
        $para_sort = $this->arg_sort($para_filter);
        //生成加密字符串
        $prestr = $this->create_string($para_sort);

        $isSgin = $this->md5_verify($prestr, $sign, Config::get('teegon.client_secret'));

        return $isSgin;
    }

    public function sign($para_temp){
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->para_filter($para_temp);

        //对待签名参数数组排序
        $para_sort = $this->arg_sort($para_filter);
        //生成加密字符串
        $prestr = $this->create_string($para_sort);

        $prestr = Config::get('teegon.client_secret') .$prestr . Config::get('teegon.client_secret');
        return strtoupper(md5($prestr));
    }


    private function para_filter($para) {
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
            if($key == "sign")continue;
            else	$para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }

    private function arg_sort($para) {
        ksort($para);
        reset($para);
        return $para;
    }

    private function create_string($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key.$val;
        }


        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

        return $arg;
    }

    private function md5_verify($prestr, $sign, $key) {
        $prestr = $key .$prestr . $key;
        $mysgin = strtoupper(md5($prestr));
        if($mysgin == $sign) {
            return true;
        }
        else {
            return false;
        }
    }



    private function on_error($err){
        abort(500);
        //throw(new TeegonServiceException($err));
    }

}

//class TeegonServiceException extends Exception{
//
//}
