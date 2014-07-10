<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-7-9
 * Time: 下午5:32
 */
$now = time();
for($a = 0;$a<4;$a++){
    $date = date('Y_m',$now);
    $file = fopen("filmRank".$date.'.csv',"a");
    fwrite($file,iconv("UTF-8","gb2312",'日期,ID,片名,日票房(万),累计票房(万)')."\r\n");
    $month_day = date('t',$now);
    for($j = 0;$j<$month_day;$j++){
        $month_time = date('Y-m-d',$now - (date('d',$now) - 1 - $j) * 86400);
        for($i = 0;$i<10;$i++){
            fwrite($file,$month_time."\r\n");
        }
    }

    fclose($file);
    $now +=$month_day * 86400;
}
