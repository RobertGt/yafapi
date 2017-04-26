<?php
/**
 * Created by PhpStorm.
 * User: robert
 * Date: 2017/4/12
 * Time: 16:31
 */
use Yaf\Request_Abstract;
use Yaf\Response_Abstract;
/**
 * 返回请求响应
 * @param Request_Abstract $request 请求对象
 * @param Response_Abstract $response 响应对象
 * @param int $code 响应编码
 * @param array $datas 响应数据
 * @param string $msg 响应文字
 */
function HttpResponse(Request_Abstract $request, Response_Abstract $response,$code = 200,$msg = "Success",$datas = array()){
    $responseBody['error'] = $code;
    $responseBody['reason'] = $code == 200?$msg:$msg=="Success"?HTTPCode::$HTTPCode[$code]:$msg;
    if(!empty($_SERVER['HTTP_X_CA_ENCRYPT'])){

    }
    $Accept = $request->getServer("HTTP_ACCEPT");
    if(!empty($datas))$responseBody['result'] = $datas;
    $Accept = strtolower($Accept);
    if(strpos($Accept,HTTPCode::ACCEPT_JSON)!==false){
        $ContentType = "application/json; charset=utf-8";
        $Body = json_encode($responseBody);
    }else if(strpos($Accept,HTTPCode::ACCEPT_XML)!==false){
        $ContentType = "Content-Type:text/xml; charset=utf-8";
        $Body = xml_encode($responseBody);
    }else if(strpos($Accept,HTTPCode::ACCEPT_TEXT)!==false && !empty($datas) && !is_array($datas)){
        $ContentType = 'Content-Type:text/html; charset=utf-8';
        $Body = $datas;
    }else{
        $ContentType = "application/json; charset=utf-8";
        $Body = json_encode($responseBody);
    }
    $response->setHeader( 'Content-Type', $ContentType);
    $response->setBody($Body);
    $response->setHeader($request->getServer('SERVER_PROTOCOL'), HTTPCode::$HTTPCode[$code]);
    $response->response();
    exit();
}

/**
 * XML编码
 * @param mixed $data 数据
 * @param string $root 根节点名
 * @param string $item 数字索引的子节点名
 * @param string $id   数字索引子节点key转换的属性名
 * @param string $encoding 数据编码
 * @return string
 */
function xml_encode($data, $root='mapgoo', $item='item', $id='key', $encoding='utf-8') {
    $xml    = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
    $xml   .= "<{$root}>";
    $xml   .= data_to_xml($data, $item, $id);
    $xml   .= "</{$root}>";
    return $xml;
}
/**
 * 数据XML编码
 * @param mixed  $data 数据
 * @param string $item 数字索引时的节点名称
 * @param string $id   数字索引key转换为的属性名
 * @return string
 */
function data_to_xml($data, $item='item', $id='key') {
    $xml = $attr = '';
    foreach ($data as $key => $val) {
        if(is_numeric($key)){
            $id && $attr = " {$id}=\"{$key}\"";
            $key  = $item;
        }
        $xml    .=  "<{$key}{$attr}>";
        $xml    .=  (is_array($val) || is_object($val)) ? data_to_xml($val, $item, $id) : $val;
        $xml    .=  "</{$key}>";
    }
    return $xml;
}