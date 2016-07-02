<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/7/2
 * Time: 11:47
 */

class SetController extends Controller
{

    public function index(){

    }

    public function clearCache(){
        $cache = Cache::initCacheMode(C('SF_CACHE_MODE'));
        $cache->clearCache();
    }
} 