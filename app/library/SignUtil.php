<?php

/**
 * Created by PhpStorm.
 * User: robert
 * Date: 2017/4/18
 * Time: 10:06
 */
class SignUtil
{
    /**
     * 构建待签名
     * @param $method 请求方式
     * @param $secret AppSecret
     * @param $headers 请求头
     * @param $fromdata 请求参数
     * @param $signHeaderPrefixList 需要进行签名的字段
     * @return string
     */
    public static function Sign($method, $secret, &$headers, $fromdata, $signHeaderPrefixList)
    {
        $signStr = self::BuildStringToSign($method, $headers, $fromdata, $signHeaderPrefixList);
        return base64_encode(hash_hmac('sha256', $signStr, $secret, true));
    }

    /**
     * 构建待签名path+(header+query)
     * @param $path  访问path
     * @param $headers  请求头
     * @param $fromdata  请求参数
     * @param $signHeaderPrefixList 需要进行签名的字段
     * @return string
     */
    private static function BuildStringToSign($method, &$headers, $fromdata, $signHeaderPrefixList)
    {
        $sb = "";
        $sb.= $method;
        $sb.= HTTPCode::LF;
        if (!empty($headers[HTTPCode::ACCEPT])) {
            $sb.= $headers[HTTPCode::ACCEPT];
        }
        $sb.= HTTPCode::LF;
        if (!empty($headers[HTTPCode::CONTENT_TYPE])) {
            $sb.= $headers[HTTPCode::CONTENT_TYPE];
        }
        $sb.= HTTPCode::LF;
        if (!empty($headers[HTTPCode::X_CA_TIMESTAMP])) {
            $sb.= $headers[HTTPCode::X_CA_TIMESTAMP];
        }
        $sb.= HTTPCode::LF;
        $sb.= self::BuildHeaders($signHeaderPrefixList);
        ksort($fromdata);
        $sb.= !empty($fromdata)?http_build_query($fromdata):"";
        return $sb;
    }
    /**
     * 构建待签名Http头
     *
     * @param signHeaderPrefixList 自定义参与签名Header
     * @return string
     */
    private static function BuildHeaders($signHeaderPrefixList)
    {
        $sb = "";
        if (!empty($signHeaderPrefixList))
        {
            ksort($signHeaderPrefixList);
            foreach ($signHeaderPrefixList as $itemKey => $itemValue){
                $sb.=$itemKey;
                $sb.=HTTPCode::SPE2;
                if(0 < strlen($itemValue)){
                    $sb.=$itemValue;
                }
                $sb.=HTTPCode::LF;
            }
        }
        return $sb;
    }
}