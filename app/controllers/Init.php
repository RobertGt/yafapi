<?php

/**
 * Created by PhpStorm.
 * User: robert
 * Date: 2017/4/13
 * Time: 11:07
 */
use Yaf\Dispatcher;
use Yaf\Controller_Abstract;
use Yaf\Registry;

class InitController extends Controller_Abstract
{
    protected $fromDatas = array();
    protected $apiArr = array();
    protected $loginUser = 0;
    public function init()
    {
        $method = strtoupper($this->getRequest()->method);
        $this->apiArr = Registry::get("AppParams");
        $headers = $this->BasicHeader();
        $signHeaderPrefixList = $this->BasicHeaderPrefix();
        $this->fromDatas = $this->getPostDatas($method);
        $signStr = SignUtil::Sign($method,$this->apiArr['AppKey'],$headers,$this->fromDatas,$signHeaderPrefixList);
        if($signStr != $this->getRequest()->getServer(HTTPCode::$Headers["X-Ca-Signature"])){
            HttpResponse($this->getRequest(),$this->getResponse(),401,Language::$msg['Invalid_Signature']);
        }
        //是否需要登录才可以访问接口
        if($this->apiArr['IsLogin']){
            $loginToken = $this->getRequest()->getServer(HTTPCode::$Headers['X-Ca-Login-Token'],"");
            if(!$this->checkUserToken($loginToken))HttpResponse($this->getRequest(),$this->getResponse(),403,Language::$msg['Invalid_Login_Token']);
        }
    }

    /**
     * 获取必须签名头部字段
     * @return array
     */
    private function BasicHeader(){
        $headers = array();
        $headers[HTTPCode::ACCEPT] = $this->getRequest()->getServer(HTTPCode::$Headers[HTTPCode::ACCEPT],"");
        $headers[HTTPCode::CONTENT_TYPE] = $this->getRequest()->getServer(HTTPCode::$Headers[HTTPCode::CONTENT_TYPE],"");
        $headers[HTTPCode::X_CA_TIMESTAMP] = $this->getRequest()->getServer(HTTPCode::$Headers[HTTPCode::X_CA_TIMESTAMP],"");
        return $headers;
    }

    /**
     * 需要签名的Header
     * @return array
     */
    private function BasicHeaderPrefix(){
        $signHeaderPrefixList = array();
        $signHeaderPrefixList = explode(HTTPCode::SPE1,$this->getRequest()->getServer(HTTPCode::$Headers['X-Ca-Signature-Headers'],""));
        $signHeaderPrefixList = array_flip($signHeaderPrefixList);
        unset($signHeaderPrefixList[HTTPCode::ACCEPT]);
        unset($signHeaderPrefixList[HTTPCode::CONTENT_TYPE]);
        unset($signHeaderPrefixList[HTTPCode::X_CA_TIMESTAMP]);
        $arr = array();
        foreach($signHeaderPrefixList as $key=>$val){
            if(!empty($key) && strpos($key,HTTPCode::CA_HEADER_TO_SIGN_PREFIX_SYSTEM)!== false){
                $arr[$key] = $this->getRequest()->getServer(HTTPCode::$Headers[$key],"");
            }
        }
        return $arr;
    }

    /**
     * 获取提交数据
     * @param string $method 请求方式
     * @return array
     */
    private function getPostDatas($method = ""){
        $fromData = array();
        switch ($method){
            case "GET":
                $fromData = $_GET;
                break;
            case "POST":
                $fromData = $_POST;
                break;
            default:
                parse_str(file_get_contents('php://input'), $fromData);
                break;
        }
        return $fromData;
    }

    /**
     * 检测用户UserTserToken的有效性
     * @param string $token
     * @return bool
     */
    private function checkUserToken($token = ""){
        if(!$token)return false;
        $redisModel = new RedisModel();
        return $redisModel->checkLoginToken($token);
    }
    /**
     * 输出返回信息
     * @param int $code 输出码
     * @param string $msg  输入内容
     * @param array $datas 输入数据
     */
    protected function returnData($code = 200,$msg = "Success",$datas = array()){
        HttpResponse($this->getRequest(),$this->getResponse(),$code,$msg,$datas);
    }
}