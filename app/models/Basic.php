<?php

/**
 * Created by PhpStorm.
 * User: robert
 * Date: 2017/4/21
 * Time: 17:08
 */
use Yaf\Registry;
class BasicModel
{
    protected $_db = "";
    public function __construct(){
        $this->_db = Registry::get('_db'); //从Registry将DB对象取出
    }
    protected function setRegistry($name,$datas){
        Registry::set($name,$datas);
    }
}