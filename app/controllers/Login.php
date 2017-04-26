<?php

/**
 * Created by PhpStorm.
 * User: robert
 * Date: 2017/4/21
 * Time: 16:19
 */
class LoginController extends InitController
{
    /**
     * 用户登录接口
     * return login token
     */
    public function UserLoginAction(){
        $Username = $this->fromDatas['Username'];
        $Password = $this->fromDatas['Password'];
        $UserModel = new BasUserModel();
        $LoginToken = $UserModel->UserLoginByUsernameAndPassword($Username,$Password);
        switch ($LoginToken){
            case 1:
                $this->returnData(412);
                break;
            case 2:
                $this->returnData(403,Language::$msg['User_Is_Disabled']);
                break;
            case 3:
                $this->returnData(403,Language::$msg['Password_Is_Error']);
                break;
            case 4:
                $this->returnData(500);
                break;
        }
        $redisModel = new RedisModel();
        $setRedis = $redisModel->setUserLogin($LoginToken);
        if($setRedis){
            $this->returnData(200,"Success",array("LoginToken"=>$LoginToken['LoginToken']));
        }else{
            $this->returnData(500);
        }
    }
}