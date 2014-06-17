<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-17
 * Time: 下午1:35
 */
//待测试
class CreateHtmlController {

    public function curlCreate(){
        $url = H('tools/List/index');
        echo $url;
        $content = CURL::get($url);
        echo $content;
    }
} 