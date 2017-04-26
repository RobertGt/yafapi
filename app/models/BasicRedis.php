<?php

/**
 * Created by PhpStorm.
 * User: robert
 * Date: 2017/4/17
 * Time: 9:27
 */
class BasicRedisModel {
    public $redis;
    public function init($config = array()) {
        $config['server'] = !$config['server']? '127.0.0.1':$config['server'];
        $config['port'] = !$config['port']?'6379':$config['port'];
        $this->redis = new Redis();
        $this->redis->connect($config['server'], $config['port']);
        return $this;
    }
    //设置值
    public function set($key, $value, $timeOut = 0) {
        if(is_array($value))$value = json_encode($value,true);
        $retRes = $timeOut > 0?$this->redis->setex($key,$timeOut,$value):$this->redis->set($key, $value);
        return $retRes;
    }
    //获取值
    public function get($key) {
        $result = $this->redis->get($key);
        return $result;
    }
    //删除一条数据
    public function rm($key) {
        return $this->redis->delete($key);
    }
    //清空数据
    public function flushAll() {
        return $this->redis->flushAll();
    }
    //数据入队列
    public function push($key, $value ,$right = true) {
        $value = json_encode($value);
        return $right ? $this->redis->rPush($key, $value) : $this->redis->lPush($key, $value);
    }
    //数据出队列
    public function pop($key , $left = true) {
        $val = $left ? $this->redis->lPop($key) : $this->redis->rPop($key);
        return json_decode($val);
    }
    //数据自增
    public function setInc($key) {
        return $this->redis->incr($key);
    }
    //数据自减
    public function setDec($key) {
        return $this->redis->decr($key);
    }
    //是否存在
    public function exists($key) {
        return $this->redis->exists($key);
    }
    public function getRedis(){
        return $this->redis;
    }
}