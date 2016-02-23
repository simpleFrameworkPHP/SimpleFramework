<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/2/23
 * Time: 22:42
 */
include_once(dirname(dirname(dirname(dirname(__FILE__))))."/core/cron.php");
$today = date('Y-m-d');
$i = 1;
$json = array();
//$city = urlencode("大连");
while(($json['success'] && !empty($json['content']['result'])) || $i == 1){
    $url = "http://www.lagou.com/jobs/positionAjax.json?px=new&first=false&pn=$i";
    $url = isset($city) ? $url."&city=$city" : $url;
    $json = getHtmlData($url);
    $json = json_decode($json,true);
    $data = $json['content']['result'];
    foreach($data as $row){
        $row['companyLabelList'] = implode(' ',$row['companyLabelList']);
        $row['addTime'] = $today;
        $model = M('',3);
        $model->table(array("view_lagou_position"))->addKeyUp($row);
    }
    //echo 'data:'.json_encode($data);
    echo  'page:'.$i."\n";
    sleep(3);
    $i++;
}