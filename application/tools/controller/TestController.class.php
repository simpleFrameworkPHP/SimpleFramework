<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 17-12-31
 * Time: 下午5:15
 */

class TestController extends Controller {

    public function index()
    {
        $all_list = json_encode($this->initA());
        $this->assign('all_list', $all_list);
        $this->display();
    }


    private function initA()
    {
        $list = [];
        $i = 1;
        while($i < 111){
            ++$i;
            $list[] = $i++;
        }
        return $list;
    }
}