<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-6-16
 * Time: 下午1:31
 */

class View {

    function replaceContent($content){
        $content = preg_replace(array('/{\$(\w+[^\{\}]*)}/','/{:(\w+)(\([^\{\}]*\))}/'),array('<?php echo $\1;?>','<?php echo \1\2;?>'),$content);
        //待优化---扩展常量数组
        $content = str_replace(array('__ROOT__','__JSROOT__','__THEME__','__PUBLIC__','__PLROOT__','UPLOAD_ROOT'),array(__ROOT__,__JSROOT__,__THEME__,__PUBLIC__,__PLROOT__,UPLOAD_ROOT),$content);
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