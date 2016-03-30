<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/3/30
 * Time: 上午8:44
 */

class PositionController extends BaseController {

    public function index(){
        $model = M('',0);
        if($_REQUEST['city']){
            $where['city'] = $_REQUEST['city'];
        }
        $where['add_time'] = $this->getLastLogTime('model_position');
//        $where['positionName'] = array("LIKE","%php%");
        $data = $model->table(array("model_position"))->where($where)->limit(1000)->select();

//        echo "<pre>";
//        print_r($data);exit;
        $columns = array();
        if(!empty($data)){
            foreach($data as $key => $item){
                $item['position_id'] = "<a href='http://www.lagou.com/jobs/".$item['position_id'].".html'>点击查看</a>";
                $item['id'] = $key;
                $data[$key] = $item;
            }
            $columns = array_keys(current($data));
        }

        $this->assign('where',$_REQUEST);
        $this->assign('columns',$columns);
        $this->assign('data',$data);
        $this->display();
    }

} 