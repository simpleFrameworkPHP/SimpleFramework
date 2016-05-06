<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-17
 * Time: 上午11:31
 */
//待测试
class CURL {

    static $proxy_opt = array('host'=>CURLOPT_PROXY,    //代理端口号
        'user:pass'=>CURLOPT_PROXYUSERPWD,              //登录用户名：密码
        'type'=>CURLOPT_PROXYTYPE,                       //代理类型Either CURLPROXY_HTTP (default) or CURLPROXY_SOCKS5.
        'port'=>CURLOPT_PROXYPORT,                       //代理机端口号
    );

    static $_timeout = 5;
    static $opt;

    static function init($url, $port, $proxy,$timeout = 0){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_PORT ,$port);
        $timeout = $timeout ? $timeout : self::$_timeout;
        curl_setopt($ch,CURLOPT_TIMEOUT,$timeout);
        curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        self::proxy($ch,$proxy);
        return $ch;
    }

    static function proxy($ch,$param){
        //设置代理的参数
        if(is_array($param) && !empty($param)){
            foreach($param as $key=>$value){
                curl_setopt($ch,self::$opt[$key] ,$value);
            }
        }
    }

    /**
     * @param string  $url      请求url
     * @param integer $port     端口号
     * @param string  $proxy    代理参数 结构如本类中$proxy_opt
     * @param integer $time_out 超时时间
     * @return mixed        数据
     */
    public static function get($url, $port = 80, $proxy ='',$time_out = 5){
        $ch = self::init($url,$port,$proxy,$time_out);
        return self::runCURL($ch);
    }

    /**
     * @param string  $url      请求url
     * @param array   $param    参数  array('key'=>'value')
     * @param integer $port     端口号
     * @param string  $proxy    代理参数 结构如本类中$proxy_opt
     * @param integer $time_out 超时时间
     * @param array   $header   报文头
     * @return mixed        数据
     */
    public static function post($url, $param, $port = 80, $proxy = '',$time_out = 5,$header = array()){
        $ch = self::init($url,$port,$proxy,$time_out);
        curl_setopt($ch,CURLOPT_POST,true);
        $param_str = '';
        if(is_array($param) && count($param)){
            $data = array();
            foreach($param as $key=>$value){
                $data[] = $key.'='.urlencode($value);
            }
            $param_str = implode('&',$data);
        }
        if(!empty($header)){
            curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header);//array ('X-ptype: like me')
        }

        curl_setopt($ch,CURLOPT_POSTFIELDS,$param_str);
        return self::runCURL($ch);
    }

    static function runCURL($ch){
        $data = curl_exec($ch);//运行curl
        if(curl_errno($ch)){
           errorPage('请求过程中出错了', curl_error($ch),curl_errno($ch));
            Log::write('CRUL ERROR',curl_error($ch).' : '.curl_errno($ch));
        }
        curl_close($ch);
        return $data;
    }
} 