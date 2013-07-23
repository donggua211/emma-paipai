<?php
/**
 * OAuth授权类
 * @author xiaopengzhu <xp_zhu@qq.com>
 * @version 2.0 2012-04-20
 */
class OAuth
{
    public $client_id = '';
    public $client_secret = '';
    
    private $accessTokenURL = 'https://open.t.qq.com/cgi-bin/oauth2/access_token';
    private $authorizeURL = 'https://open.t.qq.com/cgi-bin/oauth2/authorize';

    /**
     * 初始化
     * @param $client_id 即 appid
     * @param $client_secret 即 appkey
     * @return
     */
    public function __construct()
    {
		global $weibo2wp;
		
        $this->client_id = $weibo2wp->client_id;
        $this->client_secret = $weibo2wp->client_secret;
    }

    /**
     * 获取授权URL
     * @param $redirect_uri 授权成功后的回调地址，即第三方应用的url
     * @param $response_type 授权类型，为code
     * @param $wap 用于指定手机授权页的版本，默认PC，值为1时跳到wap1.0的授权页，为2时同理
     * @return string
     */
    public function getAuthorizeURL($redirect_uri, $response_type = 'code', $wap = false)
    {
        $params = array(
            'client_id' => $this->client_id,
            'redirect_uri' => $redirect_uri,
            'response_type' => $response_type,
            'wap' => '',
        );
        return $this->authorizeURL.'?'.http_build_query($params);
    }

    /**
     * 获取请求token的url
     * @param $code 调用authorize时返回的code
     * @param $redirect_uri 回调地址，必须和请求code时的redirect_uri一致
     * @return string
     */
    public function getAccessToken($code, $redirect_uri)
    {
        $params = array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $redirect_uri
        );
        return $this->accessTokenURL.'?'.http_build_query($params);
    }
    
    /**
     * 刷新授权信息
     * 此处以SESSION形式存储做演示，实际使用场景请做相应的修改
     */
    public function refreshToken()
    {
        $params = array(
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $_SESSION['t_refresh_token']
        );
        $url = $this->accessTokenURL.'?'.http_build_query($params);
        $r = Http::request($url);
        parse_str($r, $out);
        if ($out['access_token']) {//获取成功
            $_SESSION['t_access_token'] = $out['access_token'];
            $_SESSION['t_refresh_token'] = $out['refresh_token'];
            $_SESSION['t_expire_in'] = $out['expires_in'];
            return $out;
        } else {
            return $r;
        }
    }
    
    /**
     * 验证授权是否有效
     */
    public function checkOAuthValid()
    {
        $r = json_decode(Tencent::api('user/info'), true);
        if ($r['data']['name']) {
            return true;
        } else {
            self::clearOAuthInfo();
            return false;
        }
    }
}