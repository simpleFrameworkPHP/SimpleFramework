<?php

/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2016/5/3
 * Time: 11:41
 */
class FastFormController extends Controller
{
    const DATA_API_SECRET_KEY = '';

    public function index(){
        $url_list = $this->getList();
        if(!isset($_REQUEST['t'])){
            $cur = current($url_list);
        } else {
            $cur = $_REQUEST['t'];
        }
        $data = array();
        if($_REQUEST['url']) {
            $url = $_REQUEST['url'];
            unset($_POST['url']);
            $json = $this->curl($url, $_POST);
            $data = json_decode($json,true);
            if(!empty($data) && isset($data['data'])){
                $data = $data['data'];
            }elseif(!empty($data) && !isset($data['data'])){
                var_export($data);
            } else {
                echo $json;
            }
        }
        $this->assign('data',$data);
        $this->assign('cur',$cur);
        $this->assign('url_list',$url_list);
        $this->assign('form_str',$this->createForm($cur,$_POST));
        $this->display();
    }

    private function createForm($t,$post_data){
        $data = $this->getData();
        $str = '';
        if(isset($data[$t])){
            $str = '<ul>';
            foreach($data[$t]['form'] as $key=>$item){
                $str .= '<li style="margin: 3px;">';
                $name_str = 'name="'.$key.'"';
                $remark = '<span style="width: 100px;display: inline-block;">'.$item['remark'].':</span>';
                switch($item['type']){
                    case 'hidden':
                        $str .= '<input type="hidden" '.$name_str.' value="'.$item['data'].'" />';
                        break;
                    case 'select':
                        $str .= $remark.'<select '.$name_str.'>';
                        foreach($item['data'] as $kk=>$vv){
                            $str .= '<option value="'.$vv.'" ';
                            if($post_data[$key] == $vv){
                                $str .= 'selected';
                            }
                            $str .= '>'.$kk.'</option>';
                        }
                        $str .= '</select>';
                        break;
                    case 'input':
                        $value = (isset($post_data[$key]) && $post_data[$key] != '') ? $post_data[$key] : $item['data'];
                        $str .= $remark.'<input type="input" '.$name_str.' value="'.$value.'"/>';
                        break;
                }
                $str .= '</li>';
            }
            $str .= '</ul>';
        }
        return $str;
    }

    private function getList(){
        $data = $this->getData();
        foreach($data as $key=>$item){
            $list[$item['name']] = $key;
        }
        return $list;
    }

    private function curl($url,$post_data){
        if (!is_string($url) || !is_array($post_data)) {
            echo 0;
        } else {
            $this->assign('url',$url);
            $this->assign('post_data',$post_data);
        }
        $str = '';
        $post_data['time'] = time();
        ksort($post_data);
        foreach ($post_data as $k => $v) {
            $str .= "$k=$v";
        }
        $str .= self::DATA_API_SECRET_KEY;
        $str_md5 = md5($str);
        $post_data['sign'] = $str_md5;
        $out_put = CURL::post($url,$post_data);
        return $out_put;
    }

    private function getData(){
        return array(
            array(
                'name'=>'院校库列表获取',
                'url'=>'http://localhost/school/DataApi/getSchoolList',
                'form'=>array(
                    'url'=>array('type'=>'hidden','data'=>'http://localhost/school/DataApi/getSchoolList'),
                    'type'=>array('type'=>'select','remark'=>'国家','data'=>array('英国'=>1,'澳洲'=>2,'日本'=>3,'日本语言'=>4,'美国'=>471)),
                    'search_sh_name'=>array('type'=>'input','remark'=>'学校名称'),
                    'search_area'=>array('type'=>'input','remark'=>'地区id'),
                    'search_city'=>array('type'=>'input','remark'=>'城市id'),
                    'search_page'=>array('type'=>'input','data'=>0,'remark'=>'页数'),
                    'limit'=>array('type'=>'input','data'=>10,'remark'=>'每页行数'),
                    'search_mj_name'=>array('type'=>'input','remark'=>'专业名称'),
                    'search_direction'=>array('type'=>'select','remark'=>'专业方向id','data'=>array('请选择'=>'','商科'=>8,'工科'=>36,'理科'=>37,'护理'=>38,'艺术'=>39,'人文科学'=>40)),
                    'search_degree'=>array('type'=>'input','remark'=>'学位id'),
                    'search_de_course'=>array('type'=>'select','remark'=>'意向课程id','data'=>array('请选择'=>'','本科预科'=>502,'本科'=>503,'硕士预科'=>504,'硕士'=>505,'文凭课程'=>511,'博士'=>512)),
                )
            ),
            array(
                'name'=>'城市列表获取',
                'url'=>'http://localhost/school/List/getCity',
                'form'=>array(
                    'area_id'=>array('type'=>'input','remark'=>'地区ID')
                )
            )
        );
    }
}