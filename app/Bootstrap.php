<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 2017/4/11
 * Time: 16:33
 */
use Yaf\Loader;
use Yaf\Registry;
use Yaf\Application;
use Yaf\Dispatcher;
use Yaf\Bootstrap_ABstract;


class Bootstrap extends Bootstrap_Abstract{
    public function _initConfig() {
        Dispatcher::getInstance()->disableView();   //关闭自动调用引擎render方法；
		$config = Application::app()->getConfig();
		Registry::set('config', $config);
	}
    //加载PHP公用文件
    public function _initLoader(Dispatcher $dispatcher)
    {
        $lg = "en";
        //Loader::import('HTTPCode.php');
        Loader::import('Functions.php');
        //Loader::import('SignUtil.php');
        Loader::import('Language/'.$lg.'.php');
        Loader::import(APPLICATION_PATH . '/vendor/autoload.php');
    }
	public function _initPlugin(Dispatcher $dispatcher) {
		$objSamplePlugin = new SamplePlugin();
		$dispatcher->registerPlugin($objSamplePlugin);
	}

	public function _initRoute(Dispatcher $dispatcher) {

	}

	public function _initView(Dispatcher $dispatcher){

	}

}
