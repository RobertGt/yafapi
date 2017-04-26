<?php

/**
 * Created by PhpStorm.
 * User: robert
 * Date: 2017/4/17
 * Time: 16:33
 */
class BasAppModel extends  BasicModel
{
    protected $_table = "bas_app";
    /**
     * 检测appkey是否有权访问接口
     * @param string $appKey app应用id
     * @param int $ApiID api接口id
     * @return bool
     */
    public function CheckAuth($appKey = "",&$apiArr){
        if(!$appKey || !$apiArr)return false;
        $where['AppKey'] = $appKey;
        $field = array("AppSecret","ApiIDs","Enable");
        $app = $this->CheckAppByWhere($where,$field);
        if(empty($app) || !empty($app['Enable']) || empty($app['ApiIDs']))return false;
        $ApiIDs = explode(",",$app['ApiIDs']);
        if(!in_array($apiArr['ApiID'],$ApiIDs))return false;
        $apiArr['AppKey'] = $app['AppSecret'];
        $this->setRegistry('AppParams',$apiArr);
        return true;
    }

    /**
     * 根据where 条件查询
     * @param array $where 查询条件
     * @param string $field 需要查询字段
     * @return mixed
     */
    public function CheckAppByWhere($where = array(),$field = "*"){
        $result = $this->_db->get($this->_table,$field,$where);
        return $result;
    }
}