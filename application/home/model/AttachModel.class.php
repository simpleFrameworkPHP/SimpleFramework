<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-10-8
 * Time: 上午11:06
 */



class AttachModel extends Model {

    var $var_table = array('attach'=>'sf_attach');

    public function addFile($save_path,$file_name,$type){
        $data['save_path'] = $save_path;
        $data['file_name'] = $file_name;
        $data['type'] = $type;
        $data['uid'] = $_SESSION['uid'];
        return $this->add($data);
    }

} 