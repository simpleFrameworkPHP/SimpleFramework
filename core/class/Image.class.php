<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-7-7
 * Time: 上午10:59
 */

class Image {

    var $img_path;//图片原地址
    var $new_path;//处理后的目标地址
    var $width;//图片原宽
    var $height;//图片原高
    var $p_width;//显示比例高
    var $p_height;//显示比例宽
    var $aim_width;//图片目标宽
    var $aim_height;//图片目标高
    var $aim_x;//图片目标水平标
    var $aim_y;//图片目标垂直标
    var $cut = 0;//剪裁 1
    var $create_func;//输出图片流的方法
    var $out_func;//制作图片流
    var $refresh;//强制刷新缩略图
    var $red;//背景填充色-red
    var $green;//背景填充色-green
    var $blue;//背景填充色-blue
    var $alpha;//背景透明度 0-100

    public function __construct($img_path,$new_path = '', $refresh = false, $red = 0, $green = 0, $blue = 0, $alpha = 100){
        $this->img_path = $img_path;
        $this->new_path = $new_path;
        $this->refresh = $refresh;
        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
        $this->alpha = $alpha;
    }

    public function thumbImage($width = '',$height = '',$cut = 0,$proportion = '',$x = '',$y = ''){
        $result = false;
        if(!strstr($this->img_path,array('http://','https://')) && file_exists($this->img_path) && ($height <> '' || $width <> '')){
            $this->aim_height = $height;
            $this->aim_width = $width;
            $proportion = $this->initImageInfo($cut,$proportion);
            $this->initNewImageInfo($cut,$x,$y);
            if($this->new_path == ''){
                $extend  =  pathinfo ( $this->img_path );
                $this->new_path = $extend['dirname'].'/thumb_'.$cut.'_'.$this->aim_width.'_'.$this->aim_height.'_'.$proportion.'_'.$this->aim_x.'_'.$this->aim_y.'_'.$extend [ "basename" ];
            }
            if(!file_exists($this->new_path) || $this->refresh){
                $create = $this->create_func;
                $src = $create($this->img_path);
                $dst = $this->fillColor($this->red,$this->green,$this->blue,$this->alpha);
                if(function_exists('imagecopyresampled'))
                    imagecopyresampled($dst, $src, $this->aim_x, $this->aim_y, 0, 0, $this->p_width, $this->p_height, $this->width, $this->height);
                else
                    imagecopyresized($dst, $src, $this->aim_x, $this->aim_y, 0, 0, $this->p_width, $this->p_height, $this->width, $this->height);
                $out = $this->out_func;
                $out($dst, $this->new_path);
                if(imagedestroy($dst) && imagedestroy($src))
                    $result =  $this->new_path;
            } else {
                $result = $this->new_path;
            }
        } else {
            $result =  $this->img_path;
        }
        return str_replace(__PATH__,'',$result);
    }

    //初始化图片信息
    public function initImageInfo($cut,$proportion){
        $img_info = getimagesize($this->img_path);
        $this->width = $img_info[0];
        $this->height = $img_info[1];
        $this->initWidth();
        $this->initHeight();
        $type  = strtolower(substr(image_type_to_extension($img_info[2]), 1));
        $this->create_func = 'imagecreatefrom' . ($type == 'jpg' ? 'jpeg' : $type);
        if($proportion == ''){
            //自动比例
            $p_width = $this->aim_width <> '' ? $this->aim_width : $this->width;
            $p_height = $this->aim_height <> '' ? $this->aim_height : $this->height;
            if($cut){
                //自动赋值比例
                $ph = $this->aim_width <> '' ? $this->aim_width/$this->width : 0;
                $pw = $this->aim_height <> '' ? $this->aim_height/$this->height : 0;
                if($ph < $pw && $ph <> 0){
                    $r_width = $this->width * $ph;echo $r_width;
                    $proportion = $ph;
                } else {
                    $r_height = $this->height * $pw;echo $r_height;
                    $proportion = $pw;
                }
                $this->p_width = isset($r_width) ? $r_width : $this->width * $proportion;
                $this->p_height = isset($r_height) ? $r_height : $this->height * $proportion;
            } else {
                $this->p_height = $p_height;
                $this->p_width = $p_width;
                $proportion = $proportion;
            }
        } else {
            $this->p_width = $this->width * $proportion;
            $this->p_height = $this->height * $proportion;
        }
        return $proportion;
    }

    //初始化目标图片信息
    public function initNewImageInfo($cut,$x,$y){
        $path = $this->new_path <> '' ? $this->new_path : $this->img_path;
        $ot = pathinfo($path, PATHINFO_EXTENSION);
        $this->out_func = 'image' . ($ot == 'jpg' ? 'jpeg' : $ot);
        //当x、y没有值时居中处理
        $this->initX($x,$cut);
        $this->initY($y,$cut);
        return true;
    }

    public function initX($x,$cut){
        if($x == '' && !is_int($x)){
            //水平居中处理
            if(!$cut){
                $this->aim_x = 0;
            } else {
                $this->aim_x = ($this->aim_width - $this->p_width) / 2;
            }
        } else {
            $this->aim_x = $x;
        }
    }

    public function initY($y,$cut){
        if($y == '' && !is_int($y)){
            //垂直居中处理
            if(!$cut){
                $this->aim_y = 0;
            } else {
                $this->aim_y = ($this->aim_height - $this->p_height) / 2;
            }
        } else {
            $this->aim_y = $y;
        }
    }

    public function initWidth(){
        if($this->aim_width == '' && $this->aim_height <> ''){
            $this->aim_width = ($this->aim_height / $this->height) * $this->width;
        }
    }

    public function initHeight(){
        if($this->aim_height == '' && $this->aim_width <> ''){
            $this->aim_height = ($this->aim_width / $this->width) * $this->height;
        }
    }

    //填充色设置
    public function fillColor($red, $green, $blue, $alpha){
        $dst = imagecreatetruecolor($this->aim_width, $this->aim_height);
        $color = imagecolorallocatealpha($dst, $red, $green, $blue, $alpha);
        imagefill($dst, 0, 0, $color);
        imagesavealpha($dst, true);
        return $dst;
    }

} 