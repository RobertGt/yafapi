<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 2017/4/11
 * Time: 16:35
 */

use Yaf\Request_Abstract;
use Yaf\Response_Abstract;
use Yaf\Plugin_Abstract;
use Yaf\Registry;

class SamplePlugin extends Plugin_Abstract {
    private $_config = array();
    private $_stages = array("test","pre","release");
    private $_db = "";
    private $_redis = "";

    /**
     * 在路由之前触发
     * @param Request_Abstract $request 请求
     * @param Response_Abstract $response 响应
     */
	public function routerStartup(Request_Abstract $request, Response_Abstract $response) 
	{
        //检查请求时间是否符合要求,超过15分钟拒绝请求
        date_default_timezone_set('PRC');
        $timeStampMs = $request->getServer(HTTPCode::$Headers['X-Ca-Timestamp']);
        $requestTime = $request->getServer("REQUEST_TIME");
        $timeStamp = substr($timeStampMs,0,-3);
        $timeOut = 15*60;
        if($requestTime - $timeStamp > $timeOut)HttpResponse($request,$response,403,Language::$msg['Invalid_TimeStamp']);
        //检查防重放
        $appKey = $request->getServer(HTTPCode::$Headers['X-Ca-Key'],0);
        $NonceStr = $request->getServer(HTTPCode::$Headers['X-Ca-Nonce'],"0");
        $CheckSum = $request->getServer(HTTPCode::$Headers['X-Ca-Check-Sum'],"checksum");
        if($CheckSum != SHA1($appKey.$NonceStr.$timeStampMs))HttpResponse($request,$response,403,Language::$msg['Invalid_CheckSum']);
        //获取请求环境
        $this->_config = Registry::get('config');
        $stage = $request->getServer(HTTPCode::$Headers['X-Ca-Stage']);
        $stage = strtolower($stage);
        if(!in_array($stage,$this->_stages)){
            HttpResponse($request,$response,403,Language::$msg['Invalid_Stages']);
        }
        //加载redis配置
        $this->registryRedis($stage);
        //检测15分钟内CheckSum是否请求过
        if($this->_redis->exists($CheckSum))HttpResponse($request,$response,403,Language::$msg['Repeat_Request']);
        if(!$this->_redis->set($CheckSum,true,$timeOut))HttpResponse($request,$response,500);
        //加载config配置
        $this->registryDb($stage);
        return true;
	}

    /**
     * 在路由之后触发
     * @param Request_Abstract $request
     * @param Response_Abstract $response
     */
	public function routerShutdown(Request_Abstract $request, Response_Abstract $response) 
	{
	    //检查API是否存在
	    $where['ModuleName'] = strtolower($request->module);
	    $where['ControllerName'] = strtolower($request->controller);
	    $where['ActionName'] = strtolower($request->action);
	    $where['IsOpen'] = 1;
	    $fieldArr = array("ApiID","Method","Params","IsEncrypt","IsLogin");
        $basApiModel = new BasApiModel();
        $apiArr = $basApiModel->CheckApiByWhere($where,$fieldArr);

        if(empty($apiArr['ApiID']))HttpResponse($request,$response,404);
        //检查请求方法是否符合API
        $method = strtolower($request->method);
        $methodArr = explode(HTTPCode::SPE1,$apiArr['Method']);
        if(!in_array($method,$methodArr))HttpResponse($request,$response,403,Language::$msg['Invalid_Method']);
        //接口是否强制加密

        if($apiArr['IsEncrypt'])$_SERVER[HTTPCode::$Headers['X-Ca-Encrypt']] = 1;
        //检查是否具备访问权限
        $appKey = $request->getServer(HTTPCode::$Headers['X-Ca-Key'],"");
        $CheckAuth = $this->CheckAuth($appKey,$apiArr);
        if($CheckAuth === false)HttpResponse($request,$response,405);
        return true;
	}

	public function dispatchLoopStartup(Request_Abstract $request, Response_Abstract $response) 
	{
		
	}

	public function preDispatch(Request_Abstract $request, Response_Abstract $response) 
	{
		
	}

	public function postDispatch(Request_Abstract $request, Response_Abstract $response) 
	{
		
	}

	public function dispatchLoopShutdown(Request_Abstract $request, Response_Abstract $response) 
	{

	}

    /**
     * 加载DB类
     * @param $stage api环境
     */
	private function registryDb($stage){
        $DbConfig = $this->_config->database->$stage->toArray();
        $this->_db = new \Medoo\Medoo($DbConfig);
        Registry::set('_db', $this->_db);
    }

    /**
     * 加载redis类
     * @param $stage  api环境
     */
    private function registryRedis($stage){
        $NoSql = $this->_config->redis->$stage->toArray();
        $RedisInit = new BasicRedisModel();
        $this->_redis = $RedisInit->init($NoSql);
        Registry::set('redis',  $this->_redis);
    }
    /**
     * 检测appkey是否有权访问接口
     * @param string $appKey app应用id
     * @param int $ApiID api接口id
     * @return bool
     */
    private function CheckAuth($appKey = "",&$apiArr){
        $basAppModel = new BasAppModel();
        return $basAppModel->CheckAuth($appKey,$apiArr);
    }
}
