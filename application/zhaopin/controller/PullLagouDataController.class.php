<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/2/19
 * Time: 上午10:55
 */

class PullLagouDataController extends Controller {

    public function writePosition(){
        $today = date('Y-m-d');
        $i = 1;
        $json = array();
        $city = urlencode("大连");
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
            echo  'page:'.$i."<br/>";
            sleep(3);
            $i++;
        }
    }

    public function showPosition(){
        $model = M('',3);
        $where['city'] = "大连";
        $where['positionName'] = array("LIKE","%php%");
        $where['addTime'] = "2016-02-23";
        $data = $model->table(array("view_lagou_position"))->where($where)->order(array('createTime'=>'DESC'))->select();

//        echo "<pre>";
//        print_r($data[0]);
        $columns = array('id','positionId','workYear','createTime','positionName','companyShortName','positionAdvantage','salary','companyName','financeStage','industryField','companySize','companyLabelList');
        //array_keys($data[0]);
        foreach($data as $key => &$item){
            $item['positionId'] = "<a href='http://www.lagou.com/jobs/".$item['positionId'].".html'>点击查看</a>";
            $item['id'] = $key;
        }
        $this->assign('columns',$columns);
        $this->assign('data',$data);
        $this->display();
    }

} 