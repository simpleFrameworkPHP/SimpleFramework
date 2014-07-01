<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-30
 * Time: 下午12:36
 */

class ExpendController extends Controller {

    public function phpExcelReader(){
        $model = M('',1);
        expendModel('PHPExcel');
        $filePath = DATA_PATH.'/test/test.xlsx';
        $excel = new SFPHPExcel();
        $data = $excel->read($filePath,0,'A',2);
        print_r($data);
    }
} 