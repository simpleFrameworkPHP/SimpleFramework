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

    public function __construct(){
        $this->file_dir = APP_PATH.'/'.$_REQUEST['a'].'/pages/'.strtolower($_REQUEST['c']);
        $this->cache_file_dir = CACHE_PATH.'/pages/'.$_REQUEST['a'].'/'.$_REQUEST['c'];
    }

    public function fetch($fun = ''){
        if($fun == ''){
            $fun = $_REQUEST['f'];
            $con = strtolower($_REQUEST['c']);
            $app = $_REQUEST['a'] ;

            $this->file_dir = APP_PATH.'/'.$app.'/pages/'.$con;
            $this->cache_file_dir = CACHE_PATH.'/pages/'.$app.'/'.$con;
        } else {
            $path = explode('/',$fun);
            $count_url = count($path);
//            $fun = (isset($path[$count_url-1]) && $path[$count_url-1] <> '') ? $path[$count_url-1] :  C('SF_DEFAULT_FUN');
//            $con = (isset($path[$count_url-2]) && $path[$count_url-2] <> '') ? $path[$count_url-2] :  C('SF_DEFAULT_CON');
//            $con = strtolower($con);
//            $app = (isset($path[$count_url-3]) && $path[$count_url-3] <> '') ? $path[$count_url-3] :  C('SF_DEFAULT_APP');
            $fun = end($path);
            array_pop($path);
            $con = strtolower(end($path) ? end($path) : $_REQUEST['c']);
            array_pop($path);
            $app = end($path) ? end($path) : $_REQUEST['a'] ;

            $this->file_dir = APP_PATH.'/'.$app.'/pages/'.$con;
            $this->cache_file_dir = CACHE_PATH.'/pages/'.$app.'/'.$con;
        }
        if($this->cache_file_dir != '' && !C('SF_REFRESH_PAGES')){
            $file_path = $this->file_dir.'/'.$fun.'.html';
            $cache_file_path = $this->cache_file_dir.'/'.$fun.'.phtml';
            if(C('SF_DEBUG') || !file_exists($cache_file_path) || !file_exists($file_path) || filemtime($file_path)>=filemtime($cache_file_path)){
                //生成模板文件不存在或生成模板文件的修改时间比实际模板文件的修改时间早即生成模板文件已过时
                $contentStr = getFileContent($file_path);
                //可以实现字符替换以达到函数改写
                $contentStr = $this->replaceContent($contentStr);
                addDir($cache_file_path);
                file_put_contents($cache_file_path,$contentStr) or Log::write('CON ERROR',$cache_file_path.'文件写入出错');
            } elseif(file_exists($cache_file_path)){
                $contentStr = file_get_contents($cache_file_path) or Log::write('CON ERROR',$cache_file_path.'文件读取出错');
            } else {
                //打开文件异常
                $this->errorPage('这个页面还没做呢，做了再找我！',$file_path.'这个文件还没创建');
                $log_title = ' CON ERROR ';
                $log_info = '访问'.$file_path.'文件出错，请检查';
            }
        } else {
            $cache_file_path = $this->file_dir.'/'.$fun.'.html';
            //生成模板文件不存在或生成模板文件的修改时间比实际模板文件的修改时间早即生成模板文件已过时
            $contentStr = getFileContent($cache_file_path);
            //可以实现字符替换以达到函数改写
            $contentStr = $this->replaceContent($contentStr);
        }
        $contentStr = $this->initDefinde($contentStr);
        //controller日志位置
        if(isset($log_title)){
            Log::write($log_title,$log_info);
        }
        // 模板阵列变量分解成为独立变量
        extract($this->params, EXTR_OVERWRITE);
        // 页面缓存
        ob_start();
        ob_implicit_flush(0);
        eval('?>'.$contentStr);
        // 获取并清空缓存
        $content = ob_get_clean();
        return $content;
    }

    public function initHtml($file,$path = '',$params = array()){
        if($path == ''){
            $path = THEME_PATH . '/html/';
        } else {
            $path = APP_PATH . '/' . $_REQUEST['a'] . '/pages/' . $path . '/';
        }
        $path .= $file . '.html';
        if(file_exists($path)){
            // 模板阵列变量分解成为独立变量
            extract($this->params, EXTR_OVERWRITE);
            if(!empty($params)){
                extract($params, EXTR_OVERWRITE);
            }
            ob_start();
            include $path;
            $content = ob_get_clean();
            $content = $this->replaceContent($content);
            $content = $this->initDefinde($content);
            return $content;
        } else {
            $this->errorPage('no page','没有找到'.$path.'这个模板');
        }
    }

    public function display($fun = '',$is_create = false,$create_file = ''){
        $content = $this->fetch($fun);
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