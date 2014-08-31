<?php
/**
 * Created by PhpStorm.
 * User: shaochen.liu
 * Date: 14-8-31
 * Time: 下午4:36
 */

class ImageInfoController extends Controller {

    public function getGPSInfo(){
        $imagePath = '/image/2014/08/31/1409474311596119.jpg';
        $info = exif_read_data(DATA_PATH.$imagePath,0,true);
//        $info = exif_read_data(DATA_PATH.'/image/2014/08/31/1.jpg',0,true);var_dump($info);
        $gps = $info['GPS'];
        var_dump($gps);
        //纬度计算
        $latitude = $this->getGps($gps['GPSLatitude']);
        if($gps['GPSLatitudeRef'] <> "N"){
            $latitude = -$latitude;
        }
        //经度计算
        $longitude = $this->getGps($gps['GPSLongitude']);
        if($gps['GPSLongitudeRef'] <> "E"){
            $longitude = -$longitude;
        }
        var_dump($latitude);
        var_dump($longitude);
        $this->assign('point',array('y'=>$latitude,'x'=>$longitude,'path'=>'./data'.$imagePath));
        $this->display();
    }

    function floatGps($degrees = 0,$minutes = 0,$seconds = 0){
        $point = 0;
        if($degrees <> 0){
            $point += $degrees;
        }
        if($minutes <> 0){
            $point += $minutes / 60;
        }
        if($seconds <> 0){
            $point += $seconds / 60 / 60;
        }
        return $point;
    }

    //Pass in GPS.GPSLatitude or GPS.GPSLongitude or something in that format
    function getGps($exifCoord,$isflaot = ture)
    {
        $degrees = count($exifCoord) > 0 ? $this->gps2Num($exifCoord[0]) : 0;
        $minutes = count($exifCoord) > 1 ? $this->gps2Num($exifCoord[1]) : 0;
        $seconds = count($exifCoord) > 2 ? $this->gps2Num($exifCoord[2]) : 0;

        //normalize
        $minutes += 60 * ($degrees - floor($degrees));
        $degrees = floor($degrees);

        $seconds += 60 * ($minutes - floor($minutes));
        $minutes = floor($minutes);

        //extra normalization, probably not necessary unless you get weird data
        if($seconds >= 60)
        {
            $minutes += floor($seconds/60.0);
            $seconds -= 60*floor($seconds/60.0);
        }

        if($minutes >= 60)
        {
            $degrees += floor($minutes/60.0);
            $minutes -= 60*floor($minutes/60.0);
        }
        if($isflaot){
            $point = $this->floatGps($degrees,  $minutes, $seconds);
        } else {
            $point = array('degrees' => $degrees, 'minutes' => $minutes, 'seconds' => $seconds);
        }
        return $point;
    }

    function gps2Num($coordPart)
    {
        $parts = explode('/', $coordPart);

        if(count($parts) <= 0)
            return 0;
        if(count($parts) == 1)
            return $parts[0];

        return floatval($parts[0]) / floatval($parts[1]);
    }
} 