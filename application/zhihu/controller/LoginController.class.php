<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/4/26
 * Time: 14:29
 */
class LoginController extends Controller
{
    public function index(){
        $zhihu = "http://www.zhihu.com/";
        $user = 'liushaochen1987@qq.com';
        $pass = 'jz123456';

        if($_POST['re_str']){
            expendModel('htmlTree');
            $login_dom = file_get_html($zhihu."#signin");
            $data['_xsrf'] = $login_dom->find("input[name=_xsrf]",0)->value;
            $data['captcha'] = $_POST['re_str'];
            $data['password'] = 'jz123456';
            $data['email'] = 'liushaochen1987@qq.com';
            $data['remember_me'] = true;
            $this->login($data);
        } else {
            $time = microtime();
            $re_img_url = $zhihu."captcha.gif?r=$time&type=login";
            $this->assign('re_img_url',$re_img_url);
            $this->display();
        }
    }

    function login($data) {
        $url = 'https://www.zhihu.com/login/email';
        $header = array(
            'host:www.zhihu.com',
//            'method:POST',
            'path:/login/email',
            'scheme:https',
            'version:HTTP/1.1',
            'accept:*/*',
            'accept-encoding:gzip, deflate',
            'accept-language:zh-CN,zh;q=0.8',
            'content-length:117',
            'content-type:application/x-www-form-urlencoded; charset=UTF-8',
            'cookie:l_n_c=1; q_c1=04489dd095c2438792898e5758a8ed07|1461632506000|1461632506000; _xsrf=ce0500fbd31c174c5fc46d855c9cb672; d_c0="ACBAITMZ1AmPTnRs-Dz7z8Gzd85RU0qMdZU=|1461632507"; _za=e29953d6-8ca1-40a5-997b-7493f8cff659; _zap=c5c468ea-0832-49d7-8f46-b785cd92a5c1; s-q=php%20%E8%AF%86%E5%88%AB%E9%AA%8C%E8%AF%81%E7%A0%81; s-i=2; sid=h6kbt8k8; l_cap_id="N2ZmNDlhOTZjMmYyNDEzOGI0MGI1YmE2ZmYzZDI0NzA=|1461662575|3f69cddb1e44f88c8e07563de3f1e8daac11c48f"; __utmt=1; login="YjAwY2IwZjEzNjQ3NDEyNjk3MTdmYWVhMjk4MTUyNzM=|1461662864|35f0e07477af8a112d7f653fa0fc1e522b99a154"; cap_id="N2Y4ZTczNDJkNjI4NDkxZDhlMzY3MmYxOTM5OGZjOTI=|1461662867|5e79a00c0e57182b65c2b5fdf477fe7bf305cfa0"; __utma=51854390.761924928.1461652868.1461653751.1461662611.3; __utmb=51854390.10.10.1461662611; __utmc=51854390; __utmz=51854390.1461662611.3.2.utmcsr=zhihu.com|utmccn=(referral)|utmcmd=referral|utmcct=/question/28763214; __utmv=51854390.000--|2=registration_date=20140715=1^3=entry_date=20160426=1; z_c0=Mi4wQUFBQW92TXlBQUFBSUVBaE14blVDUmNBQUFCaEFsVk5yc0ZHVndDUlZMZjAyVjdTbnVuaXd2WkdZelAwcC1UQ2xR|1461662894|97ac86e894e358b3f50eca147a23ebb3e3a8e21e; unlock_ticket="QUFBQW92TXlBQUFYQUFBQVlRSlZUYlk3SDFjX3RRRGlTejc5TXR2ZVh5Q3hGR240MGVXbmNBPT0=|1461662894|97379ddf32e75ff7f2891be774e2b4d5c8245e64"',
            'origin:https://www.zhihu.com',
            'referer:https://www.zhihu.com/',
            'user-agent:Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36',
            'x-requested-with:XMLHttpRequest'
        );
//        $data = CURL:: post($url, $data,443,'',30,$header);
        $ch = CURL::init($url,443,'',30);
        curl_setopt($ch,CURLOPT_POST,true);
        $param_str = '';
        if(is_array($data) && count($param)){
            foreach($param as $key=>$value){
                $data[] = $key.'='.urlencode($value);
            }
            $param_str = implode('&',$data);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        if(!empty($header)){
            curl_setopt ( $ch, CURLOPT_HTTPHEADER, $header);//array ('X-ptype: like me')
        }

        curl_setopt($ch,CURLOPT_POSTFIELDS,$param_str);
        $data = CURL::runCURL($ch);
        var_export($data);
    }
}