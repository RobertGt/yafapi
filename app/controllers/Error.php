<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 2017/4/12
 * Time: 16:31
 */
use Yaf\Controller_Abstract;

class ErrorController extends Controller_Abstract 
{
    /**
     * @param $exception 错误对象
     */
	public function errorAction($exception) 
	{
	    switch ($exception->getCode()){
            case YAF\ERR\NOTFOUND\MODULE:
            case YAF\ERR\NOTFOUND\CONTROLLER:
            case YAF\ERR\NOTFOUND\ACTION:
            case YAF\ERR\NOTFOUND\VIEW:
                $code = 404;
                break;
            case Yaf\ERR\ROUTE_FAILED:
            case Yaf\ERR\DISPATCH_FAILED:
                $code = 502;
                break;
            default:
                $code = 500;
                break;
        }
        HttpResponse($this->getRequest(),$this->getResponse(),$code);
	}
}
