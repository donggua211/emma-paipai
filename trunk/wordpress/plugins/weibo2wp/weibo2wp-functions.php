<?php

/**
 * Handle requests for the gateway by calling gateways manually if needed.
 *
 * @access public
 * @return void
 */
function weibo2wp_auth_response()
{
	if ( !empty($_GET['code']) && !empty($_GET['openid'])  && !empty($_GET['openkey']) )
	{
		global $weibo2wp;
		
		$callback = site_url();
		
		$code 		= $_GET['code'];
		$openid 	= $_GET['openid'];
		$openkey	= $_GET['openkey'];
		
		//获取授权token
		$oauth = new OAuth();
		$url = $oauth->getAccessToken($code, $callback);
		$r = Http::request($url);
		parse_str($r, $out);

		//存储授权数据
		if ( isset( $out['access_token'] ) && !empty( $out['access_token'] ) )
		{
			/*
			$_SESSION['t_access_token'] = $out['access_token'];
			$_SESSION['t_refresh_token'] = $out['refresh_token'];
			$_SESSION['t_expire_in'] = $out['expires_in'];
			$_SESSION['t_code'] = $code;
			$_SESSION['t_openid'] = $openid;
			$_SESSION['t_openkey'] = $openkey;
			*/
			
			$auth_info['access_token'] = $out['access_token'];
			$auth_info['refresh_token'] = $out['refresh_token'];
			$auth_info['expires_in'] = $out['expires_in'];
			$auth_info['code'] = $code;
			$auth_info['openid'] = $openid;
			$auth_info['openkey'] = $openkey;
			$auth_info['name'] = '';
			$auth_info['head'] = '';

			$weibo = get_weibo( $openid );
			
			if ( $weibo->exist($openid))
			{
				$weibo2wp->add_error( 'Auth failed! auth has exist!' );
			}
			else
			{
				$weibo->set( $auth_info );
				
				$user_info = $weibo->weibo_user_info();
				
				if( empty( $user_info ) || empty( $user_info['name'] ) )
				{
					$weibo2wp->add_error( 'Auth failed! can not get user_info!' );
				}
				else
				{
					$weibo->name = $user_info['name'];
					$weibo->head = $user_info['head'];
					
					$weibo->add();
					$weibo2wp->add_message( 'Auth Successful!' );
					
					
				}
			}
			
		} else {
			$weibo2wp->add_error( 'Auth failed! not access_token field!' );
		}
		
		wp_redirect( add_query_arg( 'auth', $authed, admin_url( 'admin.php?page=weibo2wp' ) ) );
		exit;
	}
}

add_action( 'init', 'weibo2wp_auth_response' );


function is_weibo()
{
	$weibo_id = get_post_meta( get_the_ID(), '_weibo_id', true );
	
	return $weibo_id ? true : false;
}