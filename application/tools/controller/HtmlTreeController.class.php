<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/2/18
 * Time: 上午11:15
 */

class HtmlTreeController extends Controller {

    function getHtml(){
        expendModel('htmlTree');
        // Create DOM from URL or file
        $dom = file_get_html("http://blog.sina.com.cn/s/blog_6a33449901012tix.html");
        echo "<pre>";
        foreach($dom->find("li") as $find)
//            print_r($find->outertext);
            print_r($find->innertext);
//            print_r($find->plaintext);


//        $dom = file_get_html("http://www.kaowei.org/ExamSeat/SelectSeatWeb/1?innerType=0&date=[%222016-06-04T00:00:00%22]&city=[%22%E5%8C%97%E4%BA%AC%22]");
//        $str = strstr($dom->innertext,"seatList");
//        $str = explode("var",$str);
//        $str = substr($str[0],11);
//        $str = explode(";",$str);
//        var_dump(json_decode($str[0],true));


//        $login_dom = file_get_html("https://ielts.etest.net.cn/login");
//        $CSRFToken = $login_dom->find("input[name=CSRFToken]",0);
//        $data['user'] = '50884081';
//        $data['pass'] = 'Liushaochen1';
//        $data['CSRFToken'] = $CSRFToken->value;
//        var_export($data);

//        $json = file_get_contents("https://ielts.etest.net.cn/myHome/50884081/queryTestSeats?queryMonths=2016-06&queryProvinces=21&productId=IELTSPBT");
//        var_export(json_decode($json,true));
    }

} 