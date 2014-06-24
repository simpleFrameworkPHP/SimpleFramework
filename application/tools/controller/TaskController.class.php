<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-24
 * Time: 下午4:29
 */

class TaskController extends Controller {

    public function index(){
        runAllTask();echo '所有任务已按照要求执行一遍';
    }
} 