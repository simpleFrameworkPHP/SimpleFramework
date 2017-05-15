<?php
/**
 * Created by PhpStorm.
 * User: liushaochen
 * Date: 16/3/8
 * Time: 上午10:47
 */

class DebugController {

    public function index(){
        echo '<pre>';
        //var_dump(debug_backtrace());
        var_export(debug_backtrace());
        echo '<hr/>';
        debug_print_backtrace();
        echo '<hr/>';
        var_dump(get_included_files());
    }
}