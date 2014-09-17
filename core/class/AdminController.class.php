<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 14-7-6
 * Time: 11:21
 */
loadFile_once(CORE_PATH.'/class/Controller.class.php','Controller','CLASS');

class AdminController extends Controller {

    public function __construct(){
        parent::__construct();
        //权限管理代码
        if(!$_SESSION['uid']){
            redirect(H('admin/home/login'));
        }
    }

} 