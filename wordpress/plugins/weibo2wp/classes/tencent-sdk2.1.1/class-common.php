<?php
/**
 * HTTP请求类
 * @author xiaopengzhu <xp_zhu@qq.com>
 * @version 2.0 2012-04-20
 */
class Http
{
    /**
     * 发起一个HTTP/HTTPS的请求
     * @param $url 接口的URL 
     * @param $params 接口参数   array('content'=>'test', 'format'=>'json');
     * @param $method 请求类型    GET|POST
     * @param $multi 图片信息
     * @param $extheaders 扩展的包头信息
     * @return string
     */
    public static function request( $url , $params = array(), $method = 'GET' , $multi = false, $extheaders = array())
    {
        if(!function_exists('curl_init')) exit('Need to open the curl extension');
        $method = strtoupper($method);
        $ci = curl_init();
        curl_setopt($ci, CURLOPT_USERAGENT, 'PHP-SDK OAuth2.0');
        curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 3);
        curl_setopt($ci, CURLOPT_TIMEOUT, 3);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ci, CURLOPT_HEADER, false);
        $headers = (array)$extheaders;
        switch ($method)
        {
            case 'POST':
                curl_setopt($ci, CURLOPT_POST, TRUE);
                if (!empty($params))
                {
                    if($multi)
                    {
                        foreach($multi as $key => $file)
                        {
                            $params[$key] = '@' . $file;
                        }
                        curl_setopt($ci, CURLOPT_POSTFIELDS, $params);
                        $headers[] = 'Expect: ';
                    }
                    else
                    {
                        curl_setopt($ci, CURLOPT_POSTFIELDS, http_build_query($params));
                    }
                }
                break;
            case 'DELETE':
            case 'GET':
                $method == 'DELETE' && curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
                if (!empty($params))
                {
                    $url = $url . (strpos($url, '?') ? '&' : '?')
                        . (is_array($params) ? http_build_query($params) : $params);
                }
                break;
        }
        curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );
        curl_setopt($ci, CURLOPT_URL, $url);
        if($headers)
        {
            curl_setopt($ci, CURLOPT_HTTPHEADER, $headers );
        }

        $response = curl_exec($ci);
        curl_close ($ci);
        return $response;
    }
}

/**
 * 公共函数类
 * @author xiaopengzhu <xp_zhu@qq.com>
 * @version 2.0 2012-04-20 *
 */
class Common
{
    //获取客户端IP
    public static function getClientIp()
    {
        if (getenv ( "HTTP_CLIENT_IP" ) && strcasecmp ( getenv ( "HTTP_CLIENT_IP" ), "unknown" ))
            $ip = getenv ( "HTTP_CLIENT_IP" );
        else if (getenv ( "HTTP_X_FORWARDED_FOR" ) && strcasecmp ( getenv ( "HTTP_X_FORWARDED_FOR" ), "unknown" ))
            $ip = getenv ( "HTTP_X_FORWARDED_FOR" );
        else if (getenv ( "REMOTE_ADDR" ) && strcasecmp ( getenv ( "REMOTE_ADDR" ), "unknown" ))
            $ip = getenv ( "REMOTE_ADDR" );
        else if (isset ( $_SERVER ['REMOTE_ADDR'] ) && $_SERVER ['REMOTE_ADDR'] && strcasecmp ( $_SERVER ['REMOTE_ADDR'], "unknown" ))
            $ip = $_SERVER ['REMOTE_ADDR'];
        else
            $ip = "unknown";
        return ($ip);
    }
}

/**
 * Openid & Openkey签名类
 * @author xiaopengzhu <xp_zhu@qq.com>
 * @version 2.0 2012-04-20
 */
class SnsSign
{
    /**
     * 生成签名
     * @param string    $method 请求方法 "get" or "post"
     * @param string    $url_path 
     * @param array     $params 表单参数
     * @param string    $secret 密钥
     */
    public static function makeSig($method, $url_path, $params, $secret) 
    {
        $mk = self::makeSource ( $method, $url_path, $params );
        $my_sign = hash_hmac ( "sha1", $mk, strtr ( $secret, '-_', '+/' ), true );
        $my_sign = base64_encode ( $my_sign );
        return $my_sign;
    }
    
    private static function makeSource($method, $url_path, $params) 
    {
        ksort ( $params );
        $strs = strtoupper($method) . '&' . rawurlencode ( $url_path ) . '&';
        $str = ""; 
        foreach ( $params as $key => $val ) { 
            $str .= "$key=$val&";
        }   
        $strc = substr ( $str, 0, strlen ( $str ) - 1 );
        return $strs . rawurlencode ( $strc );
    }
}