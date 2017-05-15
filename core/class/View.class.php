<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-16
 * Time: 下午1:31
 */

class View {

    var $params = array();

    function replaceContent($content){
        // 模板阵列变量分解成为独立变量
        extract($this->params);
        $content = @preg_replace(array('/{(\$\w+[^\{\}]*)}/','/{:(\w+)(\([^\{\}]*\))}/'),array('<?php echo \1;?>','<?php echo \1\2;?>'),$content);
        $content = preg_replace('/{:\$(.+)(\([^\{\}]*\))}/','<?php echo $this->\1\2;?>',$content);
        return $content;
    }

    public function initDefinde($content){
        //待优化---扩展常量数组
        $replay_con = array('__ROOT__'=>__ROOT__,'__JSROOT__'=>__JSROOT__,'__THEME__'=>__THEME__,'__PUBLIC__'=>__PUBLIC__,'__PLROOT__'=>__PLROOT__,'UPLOAD_ROOT'=>UPLOAD_ROOT);
        foreach($replay_con as $key=>$item){
            $define[] = $key;
            $replay_con[] = "<?php echo ".$item."; ?>";
        }
        $content = str_replace($define,$replay_con,$content);
        return $content;
    }

    function rander($content,$charset = '',$contentType = '',$is_create = false,$create_file = ''){
        if(empty($charset))  $charset = C('DEFAULT_CHARSET');
        if(empty($contentType)) $contentType = C('TMPL_CONTENT_TYPE');
        // 网页字符编码
        @header('Content-Type:'.$contentType.'; charset='.$charset);
        @header('Cache-control: '.C('HTTP_CACHE_CONTROL'));  // 页面缓存控制
        @header('X-Powered-By:Fish');
        if(!$is_create){
            echo $content;
        } else {
            addDir($create_file);
            file_put_contents($create_file,$content);
        }
    }
} 