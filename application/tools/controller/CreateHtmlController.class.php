<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-17
 * Time: 下午1:35
 */
//待测试
class CreateHtmlController {

    public function index(){
        $url = H('tools/List/index');
        $content = CURL::get($url);
        file_put_contents(CACHE_PATH.'/test.txt',$content);
    }
} 