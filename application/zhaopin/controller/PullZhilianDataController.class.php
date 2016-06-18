<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/4/20
 * Time: 08:27
 */

class PullZhilianDataController extends BaseController {

    public function index(){
        zhilian::addData();
    }
} 