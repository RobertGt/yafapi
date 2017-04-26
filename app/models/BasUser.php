<?php

/**
 * Created by PhpStorm.
 * User: robert
 * Date: 2017/4/21
 * Time: 17:06
 */
class BasUserModel extends  BasicModel
{
    protected $_table = "bas_user";

    /**
     * 用户登录检测
     * @param string $Username 用户名
     * @param string $Password 密码
     * @return int
     */
    public function UserLoginByUsernameAndPassword($Username = "",$Password = ""){
        if(!$Username || !$Password)return 1;               //账户名密码为空
        $where['Username'] = $Username;
        $where['IsDelete'] = 0;
        $field = array("UserID","Password","Salt","UserState","LoginToken");
        $UserInfo = $this->_db->get($this->_table,$field,$where);
        if($UserInfo['UserState'] == 1)return 2;            //账户已被禁用
        if($UserInfo['Password'] != md5(md5($Password.$UserInfo['Salt'])))return 3;         //密码错误
        $UserLoginToken = self::getLgoinToken($UserInfo['UserID']);
        $where = array();
        $UserInfo['OldLoginToken'] = $UserInfo['LoginToken'];
        $where['UserID'] = &$UserInfo['UserID'];
        $saveData['LastLoginTime'] = time();
        $UserInfo['LoginToken'] = $saveData['LoginToken'] = $UserLoginToken;
        $setOk = $this->setUserByWhere($where,$saveData);
        if($setOk){
            return $UserInfo;
        }else{
            return 4; //修改失败
        }
    }
    public function setUserByWhere($where = array(),$saveData = array()){
        return $this->_db->update($this->_table,$saveData,$where);
    }
    /**
     * 生成LgoinToken
     * @param $UserID
     */
    private static function getLgoinToken($UserID){
        return md5(time().$UserID.rand(1000,9999));
    }
}