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
    var $params = array();

    public function __construct(){
        $this->file_dir = APP_PATH.'/'.$_REQUEST['a'].'/pages/'.strtolower($_REQUEST['c']);
        $this->cache_file_dir = CACHE_PATH.'/pages/'.$_REQUEST['a'].'/'.$_REQUEST['c'];
    }

    public function display($fun = '',$is_create = false,$create_file = ''){
        if($fun == ''){
            $fun = $_REQUEST['f'];
        } else {
            $path = explode('/',$fun);
            $count_url = count($path);
            $fun = (isset($path[$count_url-1]) && $path[$count_url-1] <> '') ? $path[$count_url-1] :  C('SF_DEFAULT_FUN');
            $con = (isset($path[$count_url-2]) && $path[$count_url-2] <> '') ? $path[$count_url-2] :  C('SF_DEFAULT_CON');
            $con = strtolower($con);
            $app = (isset($path[$count_url-3]) && $path[$count_url-3] <> '') ? $path[$count_url-3] :  C('SF_DEFAULT_APP');
            $this->file_dir = APP_PATH.'/'.$app.'/pages/'.$con;
            $this->cache_file_dir = CACHE_PATH.'/pages/'.$app.'/'.$con;
        }
        if($this->cache_file_dir != ''){
            $file_path = $this->file_dir.'/'.$fun.'.html';
            $cache_file_path = $this->cache_file_dir.'/'.$fun.'.phtml';
            if(C('SF_REFRESH_PAGES') || !file_exists($cache_file_path) || filemtime($file_path)>=filemtime($cache_file_path) || !file_exists($file_path)){
                // 模板阵列变量分解成为独立变量
                extract($this->params, EXTR_OVERWRITE);
                //生成模板文件不存在或生成模板文件的修改时间比实际模板文件的修改时间早即生成模板文件已过时
                $contentStr = getFileContent($file_path);echo $file_path;
                //可以实现字符替换以达到函数改写
                $contentStr = $this->replaceContent($contentStr);echo $contentStr;
                addDir($cache_file_path);
                file_put_contents($cache_file_path,$contentStr) or Log::write('CON ERROR',$cache_file_path.'文件写入出错');
            } else {
                //打开文件异常
                $this->errorPage('这个页面还没做呢，做了再找我！',$file_path.'这个文件还没创建');
                $log_title = ' CON ERROR ';
                $log_info = '访问'.$file_path.'文件出错，请检查';
            }
        } else {
            $cache_file_path = $this->file_dir.'/'.$fun.'.html';
        }
        //controller日志位置
        if(isset($log_title)){
            Log::write($log_title,$log_info);
        }
        // 页面缓存
        ob_start();
        ob_implicit_flush(0);
        empty($content)?include $cache_file_path:eval('?>'.$content);
        // 获取并清空缓存
        $content = ob_get_clean();

        $this->rander($content,'','',$is_create,$create_file);
    }

    public function createHtml($fun, $create_file = ''){
        $create_file = $create_file == '' ? 'index.shtml' : $create_file;
        $this->display($fun,true,$create_file);
    }

    public function assign($key,$value){
        $this->params[$key] = $value;
    }

    public function errorPage($msg,$info,$error_code = 404){
        errorPage($msg,$info,$error_code);
    }
}