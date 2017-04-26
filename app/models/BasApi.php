<?php

/**
 * Created by PhpStorm.
 * User: robert
 * Date: 2017/4/17
 * Time: 10:53
 */
class BasApiModel extends  BasicModel
{
    protected $_table = "bas_api";
    /**
     * 根据where 条件查询
     * @param array $where 查询条件
     * @param string $field 需要查询字段
     * @return mixed
     */
    public function CheckApiByWhere($where = array(),$field = "*"){
        $result = $this->_db->get($this->_table,$field,$where);
        return $result;
    }
}