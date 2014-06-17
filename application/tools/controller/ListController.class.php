<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午3:52
 */

class ListController extends Controller {

    public function index(){
        Log::write('nihao','test');
        $this->display();
    }
} 