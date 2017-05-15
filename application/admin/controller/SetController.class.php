<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/7/2
 * Time: 11:47
 */

class SetController extends AdminController
{

    public function index(){

    }

    public function getDB(){
        $model = M('');
        $list = $model->select('SHOW TABLES;');
        $tables = array();
        foreach($list as $value){
            $tables[] = current($value);
        }
        foreach($tables as $i_table){
            $create_array = $model->find('show CREATE TABLE '.$i_table);
            $create_list[$i_table] = end($create_array);
        }
        var_dump($create_list);

        S('note_data/',$create_list);
    }

    public function clearCache(){
        $cache = Cache::initCacheMode(C('SF_CACHE_MODE'));
        $cache->clearCache();
    }
} 