<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/3/23
 * Time: 下午3:17
 */

class BaseController extends Controller {

    var $db_num = 0;

    public function __construct(){
        parent::__construct();
        $menu = C('MENU');
        $url_str = "{$_REQUEST['a']}/{$_REQUEST['c']}/{$_REQUEST['f']}";
        $menu_list = array();
        $admin = isset($_REQUEST['is_admin']) ? $_REQUEST['is_admin'] : 0;
        foreach($menu as $url => $menu_item){
            if($menu_item['role'] == 1 && !$admin)continue;
            if($url_str == $url){
                $class = 'cur';
            } else {
                $class = '';
            }
            $menu_list[] = array('class'=>$class,'title'=>$menu_item['title'],'url'=>H($url));
        }
        $this->assign('menu_list',$menu_list);
    }

    /**
     * 表格显示快发
     * @param array   $data     数据
     * @param array   $xAxis    横坐标
     * @param array   $item     图例
     * @param string  $id       图表id初始化
     * @param string  $title    图表标题
     * @param integer $x_degree 图例倾斜角度
     * @return string
     */
    public function showDyEcharts($data,$xAxis,$item,$id,$title,$x_degree = 0){

        $this->assign('json',JSON($data));
        $this->assign('xAxis',JSON($xAxis));
        $this->assign('item',JSON($item));
        $this->assign('id',$id);
        $this->assign('x_degree',$x_degree);
        $this->assign('title',$title);
        return $this->fetch('common/index_dy_echarts');
    }

    public function showPieEcharts($data,$item,$id,$title){
        $this->assign('json',JSON($data));
        $this->assign('item',JSON($item));
        $this->assign('id',$id);
        $this->assign('title',$title);
        return $this->fetch('common/index_pie_echarts');
    }
} 