<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午3:48
 */

class Controller {
    public function display($fun = ''){
        if($fun == ''){
            $fun = $_REQUEST['fun'];
        }
        $file_path = APP_PATH.'/'.$_REQUEST['app'].'/pages/'.$_REQUEST['act'].'/'.$fun.'.html';
        $cache_file_path = CACHE_PATH.'/pages/'.$_REQUEST['app'].'/'.$_REQUEST['act'].'/'.$fun.'.phtml';
        if(C('refresh_pages') || !file_exists($cache_file_path || filemtime($file_path)>=filemtime($cache_file_path))){
            //生成模板文件不存在或生成模板文件的修改时间比实际模板文件的修改时间早即生成模板文件已过时
            $contentStr = file_get_contents($file_path);
            //可以实现字符替换以达到函数改写
            $contentStr = $this->replaceContent($contentStr);
            addDir($cache_file_path);
            file_put_contents($cache_file_path,$contentStr);
        }
        include $cache_file_path;
    }

    public function replaceContent($content){
        $content = preg_replace(array('/\{\$(.+)[.]?(.+)\}/','/\{\:(.+)(.+)\}/'),array('<?php echo \$\1\2;?>','<?php \1\2;?>'),$content);
        return $content;
    }
} 