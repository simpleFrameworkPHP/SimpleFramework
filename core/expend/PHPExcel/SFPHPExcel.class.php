<?php
/**
 * Created by PhpStorm. class for read Excel be easy.
 * User: shaochen.liu
 * Date: 14-7-1
 * Time: 下午12:54
 */
//class_exists('PHPExcel') or expendModel('Excel');
class SFPHPExcel {

    /**
     * @param $file 文件的绝对路径
     * @param int $sheet    要读取的单页号
     * @param string $start_column  开始列
     * @param int $start_row    开始行
     * @param string $end_column    结束列
     * @param int $end_row  结束行
     * @return mixed    返回数据
     */
    public function read($file,$sheet = 0,$start_column = 'A',$start_row = 1,$end_column = '',$end_row = 0){
        $PHPExcel = $this->createPHPExcel($file);
        /**读取excel文件中的第一个工作表*/
        $currentSheet = $PHPExcel->getSheet($sheet);

        /**取得最大的列号*/
        $allColumn = $end_column == '' ? $currentSheet->getHighestColumn() : $end_column;
        /**取得一共有多少行*/
        $allRow = $end_row == 0 ? $currentSheet->getHighestRow() : $end_row;
        for($currentColumn= $start_column;$currentColumn<= $allColumn; $currentColumn++){
            $column[$currentColumn] = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65,$start_row)->getValue();
        }
        $name = array();$i = 0;
        /**从第二行开始输出，因为excel表中第一行为列名*/
        for($currentRow = $start_row + 1;$currentRow <= $allRow;$currentRow++){
            /**从第A列开始输出*/
            for($currentColumn=$start_column;$currentColumn<=  $allColumn; $currentColumn++){
                $val = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65,$currentRow)->getValue();
                /**ord()将字符转为十进制数*/
                if($val){
                    $data[$currentRow][$column[$currentColumn]] = $val;
                }
                $count = $currentSheet->getCellByColumnAndRow(ord($currentColumn) - 65,$currentRow)->getXfIndex();
                if($count > 1){
                    if($val){
                        $name[$column[$currentColumn]] = $data[$currentRow][$column[$currentColumn]];
                    } else {
                        if(count($name)){
                            $i_column = $currentColumn;
                            while(ord($i_column) - 65 >= 0){
                                if(isset($name[$column[$i_column]])){
                                    $data[$currentRow][$column[$currentColumn]] = $name[$column[$i_column]];
                                    break;
                                }
                                $i_column = chr(ord($i_column) - 1);
                            }
                        }
                    }
                }
                /**如果输出汉字有乱码，则需将输出内容用iconv函数进行编码转换，如下将gb2312编码转为utf-8编码输出*/
//                    echo iconv('utf-8','gb2312', $val)."\t";
            }
        }
        return $data;
    }

    function createPHPExcel($file){
        $PHPExcel = false;
        if(file_exists($file)){
            /**默认用excel2007读取excel，若格式不对，则用之前的版本进行读取*/
            $PHPReader = new PHPExcel_Reader_Excel2007();
            if(!$PHPReader->canRead($file)){
                $PHPReader = new PHPExcel_Reader_Excel5();
                if(!$PHPReader->canRead($file)){
                    echo 'no Excel';
                    return ;
                }
            }
            $PHPExcel = $PHPReader->load($file);
        }
        return $PHPExcel;
    }
} 