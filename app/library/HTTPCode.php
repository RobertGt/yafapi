<?php

/**
 * Created by PhpStorm.
 * User: robert
 * Date: 2017/4/13
 * Time: 17:50
 */
class HTTPCode
{
	public static $HTTPCode = array(
		 '200' => "200 OK",
		//重定向
		 '300' => "300 Multiple Choices",
		 '301' => "301 Moved Permanently",
		 '302' => "302 Move temporarily",
		 '303' => "303 See Other",
		 '304' => "304 Not Modified",
		 '305' => "305 Use Proxy",
		 '306' => "306 Switch Proxy",
		 '307' => "307 Temporary Redirect",

		//请求错误
		 '400' => "400 Bad Request",
		 '401' => "401 Unauthorized",
		 '402' => "402 Payment Required",
		 '403' => "403 Forbidden",
		 '404' => "404 Not Found",
		 '405' => "405 Method Not Allowed",
		 '406' => "406 Not Acceptable",
		 '407' => "407 Proxy Authentication Required",
		 '408' => "408 Request Timeout",
		 '409' => "409 Conflict",
		 '410' => "410 Gone",
		 '411' => "411 Length Required",
		 '412' => "412 Precondition Failed",
		 '413' => "413 Request Entity Too Large",
		 '414' => "414 Request-URI Too Long",
		 '415' => "415 Unsupported Media Type",
		 '416' => "416 Requested Range Not Satisfiable",
		 '422' => "422 Unprocessable Entity",
		 '423' => "423 Locked",
		 '424' => "424 Failed Dependency",
		 '425' => "425 Unordered Collection",
		 '426' => "426 Upgrade Required",

		//服务器错误
		 '500' => "500 Internal Server Error",
		 '501' => "501 Not Implemented",
		 '502' => "502 Bad Gateway",
		 '503' => "503 Service Unavailable",
		 '504' => "504 Gateway Timeout",
		 '505' => "505 HTTP Version Not Supported",
		 '506' => "506 Variant Also Negotiates",
		 '507' => "507 Insufficient Storage",
		 '509' => "509 Bandwidth Limit Exceeded",
		 '510' => "510 Not Extended",
	);

    //响应头
    const ACCEPT_JSON = "application/json";
    const ACCEPT_XML = "application/xml";
    const ACCEPT_TEXT = "application/html";


    public static $Headers = array(
        //响应格式
        'Accept' => "HTTP_ACCEPT",
        //客户端参数
        'Conetnt_Type' => "HTTP_CONTENT_TYPE",
        //请求环境
        'X-Ca-Stage' => "HTTP_X_CA_STAGE",
        //请求版本
        'X-Ca-Version' => "HTTP_X_CA_VERSION",
        //防止重放
        'X-Ca-Check-Sum' => "HTTP_X_CA_CHECK_SUM",
        //签名Header
        'X-Ca-Signature' => "HTTP_X_CA_SIGNATURE",
        //所有参与签名的Header
        'X-Ca-Signature-Headers' => "HTTP_X_CA_SIGNATURE_HEADERS",
        //请求时间戳
        'X-Ca-Timestamp' => "HTTP_X_CA_TIMESTAMP",
        //随机字符串
        'X-Ca-Nonce' => "HTTP_X_CA_NONCE",
        //APP KEY
        'X-Ca-Key' => "HTTP_X_CA_KEY",
        //是否加密返回
        'X-Ca-Encrypt' => "HTTP_X_CA_ENCRYPT",
        //是否调试模式
        'X-Ca-Request-Mode' => "HTTP_X_CA_REQUEST_MODE",
        //是否登录Login-Token
        'X-Ca-Login-Token' => "HTTP_X_CA_LOGIN_TOKEN",
    );
    const ACCEPT = "Accept";
    const CONTENT_TYPE = "Conetnt_Type";
    const X_CA_TIMESTAMP = "X-Ca-Timestamp";
    //换行符
    const LF = "\n";
    //分隔符1
    const SPE1 = ",";
    //分隔符2
    const SPE2 = ":";
    //X-Ca的才会参与签名
    const CA_HEADER_TO_SIGN_PREFIX_SYSTEM = "X-Ca-";

    //登录前缀
    const LOGIN_RESIDS = "Login:";
}