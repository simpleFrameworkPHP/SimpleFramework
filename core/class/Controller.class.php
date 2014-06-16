<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-5-28
 * Time: 下午3:48
 */
loadFile_once(CORE_PATH.'/class/View.class.php','View','CLASS');
class Controller extends View {

    var $file_dir = '';
    var $cache_file_dir = '';
    var $before_content = '';

    public function __construct(){
        $this->file_dir = APP_PATH.'/'.$_REQUEST['app'].'/pages/'.$_REQUEST['act'];
        $this->cache_file_dir = CACHE_PATH.'/pages/'.$_REQUEST['app'].'/'.$_REQUEST['act'];
    }

    public function display($fun = ''){
        if($fun == ''){
            $fun = $_REQUEST['fun'];
        }
        if($this->cache_file_dir != ''){
            $file_path = $this->file_dir.'/'.$fun.'.html';
            $cache_file_path = $this->cache_file_dir.'/'.$fun.'.phtml';
            if(C('SF_REFRESH_PAGES') || !file_exists($cache_file_path) || filemtime($file_path)>=filemtime($cache_file_path) || !file_exists($file_path)){
                $contentStr = $this->before_content;
                //生成模板文件不存在或生成模板文件的修改时间比实际模板文件的修改时间早即生成模板文件已过时
                $contentStr .= file_get_contents($file_path);
                //可以实现字符替换以达到函数改写
                $contentStr = $this->replaceContent($contentStr);
                addDir($cache_file_path);
                file_put_contents($cache_file_path,$contentStr);
            } else {
                //打开文件异常
                $this->errorPage('这个页面还没做呢，做了再找我！',$file_path.'这个文件还没创建');
            }
        } else {
            $cache_file_path = $this->file_dir.'/'.$fun.'.html';
        }
        include $cache_file_path;
    }

    public function assign($key,$value){
        $this->before_content .= $this->replaceParam($key,$value);
    }

    public function replaceContent($content){
        $content = preg_replace(array('/\{\$(\w+)\}/','/{:(\w+)(\([\S+\,?]*\))}/'),array('<?php echo \$\1;?>','<?php \1\2;?>'),$content);
        return $content;
    }

    public function errorPage($msg,$info,$error_code = 404){
        errorPage($msg,$info,$error_code);
    }
}