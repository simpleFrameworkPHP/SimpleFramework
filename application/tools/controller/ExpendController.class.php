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

    public function useUeditor(){
        if($_POST['content']){
            print_r($_POST);
        }
        $this->display();
    }

    public function phpExcelWrite(){
        expendModel('PHPExcel');
        $excel = new SFPHPExcel();
        $PHPExcel = $excel->initExcel();
        $objActSheet = $PHPExcel->getActiveSheet();
        //设置默认列宽
        $objActSheet->getDefaultColumnDimension()->setWidth(12);
        $objActSheet->getColumnDimensionByColumn(2)->setWidth(25);
        $objActSheet->getColumnDimensionByColumn(9)->setWidth(25);
        $data = array(
            '1'=>array('A'=>'日期','B'=>'ID','C'=>'片名','D'=>'日票房(万)','E'=>'累计票房(万)')
        );
        //A日期
        $now = time();
        $times = 2;
        $month_day = date('t',$now);
        for($j = 0;$j<$month_day;$j++){
            $month_time = date('Y-m-d',$now - (date('d',$now) - 1 - $j) * 86400);
            for($i = 0;$i<10;$i++){
                $data[$times++]['A'] = $month_time;
            }
        }
        //数据有效性验证添加
        $film_str = $this->getFilm();
        foreach($film_str as $k1 => $value){
            $data[$k1+1]['J'] = $value;
        }

        $param = array(
            array(
                'con'=>'C2:C'.$times,
                'type'=>PHPExcel_Cell_DataValidation::TYPE_LIST,
                'data'=>'=$J$1:$J$'.($k1+1)
            ),
        );
        $excel->writer($PHPExcel,$data,'filmRank2014.xlsx',$param);
    }

    public function getFilm(){
        $url1 = 'http://www.1905.com/api/mdb/listpage.php?type=hot';//正在热映
        $url2 = 'http://www.1905.com/api/mdb/listpage.php?type=soon';//国内将映
        $content = json_decode(CURL::get($url1),true);
        foreach($content['data'] as $value){
            $film[] = $value['title'];
        }
        $content1 = json_decode(CURL::get($url2),true);
        foreach($content1['data'] as $value){
            $film[] = $value['title'];
        }
        return array_flip(array_flip($film));
    }
} 