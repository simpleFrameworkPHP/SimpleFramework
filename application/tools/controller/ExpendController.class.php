<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-30
 * Time: 下午12:36
 */

class ExpendController extends Controller {

    public function phpExcel(){
        $model = M('',1);
        explandModel('PHPExcel');
        $filePath = "D:/1.xls";
        $PHPExcel = new PHPExcel();

        /**默认用excel2007读取excel，若格式不对，则用之前的版本进行读取*/
//        $PHPReader = new PHPExcel_Reader_Excel2007();
//        if(!$PHPReader->canRead($filePath)){
            $PHPReader = new PHPExcel_Reader_Excel5();
            if(!$PHPReader->canRead($filePath)){
                echo 'no Excel';
                return ;
            }
//        }

        $PHPExcel = $PHPReader->load($filePath);
         /**读取excel文件中的第一个工作表*/
        $currentSheet = $PHPExcel->getSheet(0);

        /**取得最大的列号*/
        $allColumn = $currentSheet->getHighestColumn();
        /**取得一共有多少行*/
        $allRow = $currentSheet->getHighestRow();
        $allRow = 209;
        for($currentColumn= 'A';$currentColumn<= 'G'; $currentColumn++){
            $column[$currentColumn] = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65,2)->getValue();
        }
        $name = array();$i = 0;
        /**从第二行开始输出，因为excel表中第一行为列名*/
        for($currentRow = 3;$currentRow <= $allRow;$currentRow++){
            /**从第A列开始输出*/
            for($currentColumn= 'A';$currentColumn<=  'G'; $currentColumn++){
                $val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65,$currentRow)->getValue();
                /**ord()将字符转为十进制数*/
                if($val){
                    $data[$currentRow][$column[$currentColumn]] = $val;
                }
                if(in_array($column[$currentColumn],array('栏目名称','子栏目名称','id'))){
                    if($val){
                        $name[$column[$currentColumn]] = $data[$currentRow][$column[$currentColumn]];
                    } else {
                        $data[$currentRow][$column[$currentColumn]] = $name[$column[$currentColumn]];
                    }
                } else if($column[$currentColumn] == '规格'){
                    if($val){
                        $name[$column[$currentColumn]] = $data[$currentRow][$column[$currentColumn]];
                    } else {
                        $data[$currentRow][$column[$currentColumn]] = $name[$column[$currentColumn]];
                    }
                    $val = explode('*',$data[$currentRow][$column[$currentColumn]]);
                    $width = explode("：",$val[0]);
                    $data[$currentRow]['width'] = $width[count($width) - 1];
                    $height = explode(" ",$val[1]);
                    $data[$currentRow]['height'] = intval($height[0]);
                } else if($column[$currentColumn] == '展示时间'){
                    $data[$currentRow][$column[$currentColumn]] = strstr($data[$currentRow][$column[$currentColumn]],'第') ? preg_replace(array('/\D*(\d+)\D*/'),array('\1'),$data[$currentRow][$column[$currentColumn]]) : 0;
                } else if($val) {
                    $data[$currentRow][$column[$currentColumn]] = $val;
                }
                    /**如果输出汉字有乱码，则需将输出内容用iconv函数进行编码转换，如下将gb2312编码转为utf-8编码输出*/
//                    echo iconv('utf-8','gb2312', $val)."\t";
            }
            $ids[] = $data[$currentRow]['id'];
            $d = $model->select('SELECT * FROM cms_ads_place WHERE adids LIKE "%'.$data[$currentRow]['id'].'%"');
            foreach($d as $k){$i++;
                $i_json = json_decode($k['expend'],true);
                $i_json['passtime'] =  $data[$currentRow]['展示时间'];
                $sql = "UPDATE cms_ads_place SET placename='".$data[$currentRow]['栏目名称']."-".$data[$currentRow]['子栏目名称']."' , introduce='".$data[$currentRow]['广告位置']."',width=".$data[$currentRow]['width'].",height=".$data[$currentRow]['height'].", expend='".json_encode($i_json)."' WHERE adids LIKE '%".$data[$currentRow]['id']."%' AND placeid=".$k['placeid'];
                if(!strstr($sql,'%%') && !strstr($sql,'""')){
//                    print_r($data[$currentRow]);
                    echo $sql.';<br/>';
                }
            }

        }
        echo $i;
//        print_r(json_encode($ids));
    }
    function getData($val){
        $jd = GregorianToJD(1, 1, 1970);
        $gregorian = JDToGregorian($jd+intval($val)-25569);
        return $gregorian;/**显示格式为 “月/日/年” */
    }
} 