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
    }

} 