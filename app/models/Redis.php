<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/21
 * Time: 18:20
 */
use Yaf\Registry;
class RedisModel
{
    public $redis = "";
    public function __construct()
    {
        $this->redis = Registry::get('redis'); //从Registry将redis对象取出
    }
    /**
     * 设置用户登录ID
     * @param array $UserInfo
     * @return bool
     */
    public function setUserLogin($UserInfo = array()){
        if(empty($UserInfo))return false;
        $config = Registry::get("config");
        if(!$config->login->session->more){
            $this->redis->rm(HTTPCode::LOGIN_RESIDS.$UserInfo['OldLoginToken']);
        }
        $this->redis->set(HTTPCode::LOGIN_RESIDS.$UserInfo['LoginToken'],$UserInfo['UserID'],$config->login->session->outtime);
        return true;
    }

    /**
     * 检测token
     * @param string $token
     * @return bool
     */
    public function checkLoginToken($token = ""){
        if(!$token)return false;
        $config = Registry::get("config");
        if($UsrID = $this->redis->get(HTTPCode::LOGIN_RESIDS.$token)){
            $this->redis->set(HTTPCode::LOGIN_RESIDS.$token,$UsrID,$config->login->session->outtime);
            return true;
        }else{
            return false;
        }
    }
}