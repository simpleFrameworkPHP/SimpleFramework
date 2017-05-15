<?php
/**
 * Created by PhpStorm. class for read Excel be easy.
 * User: shaochen.liu
 * Date: 14-7-1
 * Time: 下午12:54
 */

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
        $PHPExcel = $this->PHPReader($file);
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

    function PHPReader($file){
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

    function initExcel(){
        $PHPExcel = new PHPExcel();
        //Set properties 设置文件属性
        $objProperties = $PHPExcel->getProperties();
        $objProperties->setCreator("Maarten Balliauw");
        $objProperties->setLastModifiedBy("Maarten Balliauw");
        $objProperties->setTitle("Office 2007 XLSX Test Document");
        $objProperties->setSubject("Office 2007 XLSX Test Document");
        $objProperties->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.");
        $objProperties->setKeywords("office 2007 openxml php");
        $objProperties->setCategory("Test result file");
        return $PHPExcel;
    }

    function writer($PHPExcel,array $data,$path,array $param = array()){
        //设置和获取工作单
        $PHPExcel->setActiveSheetIndex(0);
        $objActSheet = $PHPExcel->getActiveSheet();
        //写入内容
        foreach($data as $i_row=>$row){
            foreach($row as $i_col=>$call){
                $objActSheet->setCellValue($i_col.$i_row,$call);
            }
        }

        if(count($param)){
            $this->dataValidation($objActSheet,$param);
        }

        //将生成的excel数据输出或保存到某个文件中。
        $objWriter = new PHPExcel_Writer_Excel2007($PHPExcel);
        $objWriter->setOffice2003Compatibility(true);
        if($path == 'download'){
            header("Content-Type:application/vnd.ms-excel");
            header("Content-Disposition:attachment;filename=sample.xls");
            header("Pragma:no-cache");
            header("Expires:0");
            $objWriter->save('php://output');
        } else {
            addDir($path);
            $objWriter->save($path);
        }
    }

    /** 添加数据有效性
     * @param $objActSheet  工作单对象
     * @param $param array(array('con'=>'c1:c300','type'=>PHPExcel_Cell_DataValidation::TYPE_[LIST],'data'=>'绑定的数据'))
     */

    function dataValidation($objActSheet,$param){
        foreach($param as $key=>$value){
            $p = explode(':',$value['con']);
            $start = substr($p[0],1);
            $end = substr($p[1],1);
            $con = substr($p[0],0,1);
            for($i = $start;$i<$end;$i++){
                $objValidation = $objActSheet->getCell($con.$i)->getDataValidation(); //这一句为要设置数据有效性的单元格
                $objValidation -> setType($value['type'])
                    -> setErrorStyle(PHPExcel_Cell_DataValidation::STYLE_INFORMATION)
                    -> setAllowBlank(false)
                    -> setShowInputMessage(true)
                    -> setShowErrorMessage(true)
                    -> setShowDropDown(true)
                    -> setErrorTitle('输入的值有误')
                    -> setError('您输入的值不在下拉框列表内.')
                    -> setPromptTitle('设备类型')
                    -> setFormula1($value['data']);
            }
        }
    }
}