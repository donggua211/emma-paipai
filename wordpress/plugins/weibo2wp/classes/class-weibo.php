<?php
class Weibo2wp_Weibo
{
	public $access_token;
	public $refresh_token;
	public $expires_in;
	public $code;
	public $openid;
	public $openkey;
	public $name;
	public $head;
	
	//接口url
    public $apiUrlHttp = 'http://open.t.qq.com/api/';
    public $apiUrlHttps = 'https://open.t.qq.com/api/';
    
    //调试模式
    public $debug = false;
	
	public function __construct( $openid ) {
		global $weibo2wp;
		
		$this->openid = $openid;
		
		$weibo = $weibo2wp->get_auth( $this->openid );
		
		if( empty( $weibo ) )
		{
			$weibo['access_token'] = '';
			$weibo['refresh_token'] = '';
			$weibo['expires_in'] = '';
			$weibo['code'] = '';
			$weibo['openkey'] = '';
			$weibo['name'] = '';
			$weibo['head'] = '';
		}
		
		$this->access_token	 = $weibo['access_token'];
		$this->refresh_token = $weibo['refresh_token'];
		$this->expires_in	 = $weibo['expires_in'];
		$this->code			 = $weibo['code'];
		$this->openkey		 = $weibo['openkey'];
		$this->name			 = $weibo['name'];
		$this->head			 = $weibo['head'];
		
		$this->debug = $weibo2wp->debug;
    }

	public function weibo_user_info()
	{
		return $this->api('user/info');
	}
	
	public function weibo_broadcast_timeline($lastid = 0, $pagetime = 0, $pageflag = 0)
	{
		$params = array(
			'type' => '1',
			'contenttype' => '0',
			'reqnum' => 20,
			'pageflag' => $pageflag,
			'lastid' => $lastid,
			'pagetime' => $pagetime,
		);
		return $this->api('statuses/broadcast_timeline', $params);
	}
	
	public function weibo_re_list($weibo_id, $twitterid = 0, $pagetime = 0, $pageflag = 0)
	{
		$params = array(
			'rootid' => $weibo_id,
			'contenttype' => '0',
			'reqnum' => 100,
			'pageflag' => $pageflag,
			'twitterid' => $twitterid,
			'pagetime' => $pagetime,
		);
		return $this->api('t/re_list', $params);
	}
	
	public function weibo_arr()
	{
		$weibo_arr = array(
			'access_token'	 => $this->access_token,
			'refresh_token'	 => $this->refresh_token,
			'expires_in'	 => $this->expires_in,
			'code'			 => $this->code,
			'openid'		 => $this->openid,
			'openkey'		 => $this->openkey,
			'name'			 => $this->name,
			'head'			 => $this->head,
		);
		
		return $weibo_arr;
	}
	
    function add()
    {
		global $weibo2wp;
		
		$weibo_arr = $this->weibo_arr();
		
		return $weibo2wp->add_auth( $weibo_arr );
    }
	
    function delete()
    {
		global $weibo2wp;
		return $weibo2wp->delete_auth( $this->openid );
    }
	
    function exist()
    {
		global $weibo2wp;
		return $weibo2wp->check_auth_exist( $this->openid );
    }
	
	/*
	 * Type can be all or new. 
	 * All: synch all weibo
	 * new: Synch newest weibo only
	*/
    function synch( $type = 'new' )
    {
		$lastid = 0;
		$pagetime = 0;
		$pageflag = 0;
		
		do{
			$pageflag = 1;
			
			$weibo_list = $this->weibo_broadcast_timeline( $lastid, $pagetime, $pageflag );
			
			if( empty( $weibo_list ) )
				continue;
			
			$weibo_list_info = array();
			if( $weibo_list['totalnum'] == 1 )
				$weibo_list_info[] = $weibo_list['info'];
			else
				$weibo_list_info = $weibo_list['info'];
			
			foreach($weibo_list_info as $entry)
			{
				$lastid = $entry['id'];
				$pagetime = $entry['timestamp'];
				
				//if type is not all, and id already exist
				if ( $this->check_post_exist( $entry['id'] ) )
				{
					if( $type == 'new' )
						break 2;
					else
						continue;
				}
			
				$this->process_posts( $entry );
			}
		}
		while( 0 == $weibo_list['hasnext'] );
		
		//Synch Comments
		$post_ids = $this->get_post_ids();
		if( !empty( $post_ids ) )
		{
			foreach( $post_ids as $id )
			{
				$weibo_id = get_post_meta( $id, '_weibo_id', true );
				
				if( empty( $weibo_id ) )
					continue;
				
				$twitterid = 0;
				$pagetime = 0;
				$pageflag = 0;
				do{
					$pageflag = 1;
					
					$re_list = $this->weibo_re_list( $weibo_id, $twitterid, $pagetime, $pageflag );
					
					if( empty( $re_list ) )
						continue 2;
					
					$re_list_info = array();
					if( $re_list['totalnum'] == 1 )
						$re_list_info[] = $re_list['info'];
					else
						$re_list_info = $re_list['info'];
					
					foreach( $re_list_info as $re_entry )
					{
						$twitterid = $re_entry['id'];
						$pagetime = $re_entry['timestamp'];
						
						//if type is not all, and id already exist
						if ( $this->check_comment_exist( $re_entry['id'] ) )
						{
							if( $type == 'new' )
								break 2;
							else
								continue;
						}
					
						$this->process_comment( $id, $re_entry );
					}
				}
				while( 0 == $re_list['hasnext'] );
				
			}
		}
    }
	
	function delete_post()
	{
		$post_ids = $this->get_post_ids();
		
		if( !empty( $post_ids ) )
		{
			foreach( $post_ids as $id )
				wp_delete_post( $id, true );
		}
		
		return true;
	}
	
	function get_post_ids()
	{
		global $wpdb;
		
		if( empty( $this->name ) )
			return array();
		
		$post_ids = $wpdb->get_col( $wpdb->prepare("
					SELECT $wpdb->posts.ID
				    FROM $wpdb->posts
				    LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
				    WHERE $wpdb->posts.post_type = 'post'
				    AND $wpdb->postmeta.meta_key = '_weibo_name' AND $wpdb->postmeta.meta_value = '%s'", $this->name ) );
		
		return $post_ids;
	}
	
    function set( $weibo )
    {
		$this->access_token	 = $weibo['access_token'];
		$this->refresh_token = $weibo['refresh_token'];
		$this->expires_in	 = $weibo['expires_in'];
		$this->code			 = $weibo['code'];
		$this->openkey		 = $weibo['openkey'];
		$this->name			 = $weibo['name'];
		$this->head			 = $weibo['head'];
    }

    function check_auth_valid()
    {
		$r = $this->weibo_user_info();
        return $r['name'] ? true : false;
    }
	
	/*
	 * one post stuff
	 */
	function check_post_exist( $id )
	{
		global $wpdb;
		$post_id = $wpdb->get_var( $wpdb->prepare("
					SELECT $wpdb->posts.ID
				    FROM $wpdb->posts
				    LEFT JOIN $wpdb->postmeta ON ($wpdb->posts.ID = $wpdb->postmeta.post_id)
				    WHERE $wpdb->posts.post_type = 'post'
				    AND $wpdb->posts.post_status = 'publish'
				    AND $wpdb->postmeta.meta_key = '_weibo_id' AND $wpdb->postmeta.meta_value = '%s'", $id ) );
		
		return $post_id;
	}
	
	function check_comment_exist( $id )
	{
		global $wpdb;
		$post_id = $wpdb->get_var( $wpdb->prepare("
					SELECT $wpdb->comments.comment_ID
				    FROM $wpdb->comments
				    LEFT JOIN $wpdb->commentmeta ON ($wpdb->comments.comment_ID = $wpdb->commentmeta.comment_id)
				    WHERE $wpdb->commentmeta.meta_key = '_comment_id' AND $wpdb->commentmeta.meta_value = '%s'", $id ) );
		
		return $post_id;
	}
	
	function process_posts( $data ) {
	
		//Deal with data first.
		$author_name = 'weibo_' . $data['name'];
		$data['title'] = weibo_motion_2_img( strip_tags( $data['text'] ) );
		$data['text'] = weibo_motion_2_img( $data['text'] );
		
		//Check user
		if ( $user = get_user_by('login', $author_name ) )
		{
			$author_id = $user->ID;
		}
		else
		{
			$new_author = array(
            	'user_login' => $author_name,
            	'user_nicename' => $data['name'],
            	'display_name' => $data['nick'],
            	'user_pass'  => '000000',
            );

            $author_id = wp_insert_user( $new_author );
		}
		
		$postdata = array(
			'post_author' => $author_id,
			'post_content' => $data['text'],
			'post_title' => $data['title'],
			'post_excerpt' => '',
			'post_status' => 'publish',
			'comment_status' => 'open',
			'ping_status' => 'open',
			'post_date' => date( 'Y-m-d H:i:s', $data['timestamp'] ),
			'post_parent' => 0,
			'menu_order' => 0,
			'post_type' => 'post',
			'post_password' => ''
		);
			
		
		
		$post_id = wp_insert_post( $postdata );
			

		if ( is_wp_error( $post_id ) ) {
			return false;
		}
		
		//Update post meta
		update_post_meta( $post_id, '_weibo_name', $data['name'] );
		update_post_meta( $post_id, '_weibo_id', $data['id'] );
		
		//Images
		if( !empty( $data['image'] ) )
		{
			if( !is_array( $data['image'] ) )
			{
				$data['image'] = (array) $data['image'];
			}
			
			update_post_meta( $post_id, '_weibo_image', serialize( $data['image'] ) );
		}
		
		//add category
		$term_exists = term_exists( $data['nick'] . '的微博', 'category' );
		$term_id = is_array( $term_exists ) ? $term_exists['term_id'] : $term_exists;
		if ( ! $term_id ) {
			$t = wp_insert_term( $data['nick'] . '的微博', 'category' );
			if ( ! is_wp_error( $t ) )
			{
				$term_id = $t['term_id'];
			}
			else 
			{
				$term_id = 1;
			}
		}
		
		wp_set_post_terms( $post_id, $term_id, 'category' );
		
		return true;
	}
	
	function process_comment( $id, $data ) {
	
		$commentdata = array(
			'comment_post_ID' 	=> $id,
			'comment_author' 	=> $data['nick'],
			'comment_content' 	=> $data['text'],
			'comment_agent'		=> 'weibo2wp',
			'comment_approved' 	=> 1,
			'comment_date' 		=> date( 'Y-m-d H:i:s', $data['timestamp'] ),
		);
		
		$comment_id = wp_insert_comment( $commentdata );
		
		update_comment_meta( $comment_id, '_comment_id', $data['id'] );
		
		return true;
	}
	
	/**
     * 发起一个腾讯API请求
     * @param $command 接口名称 如：t/add
     * @param $params 接口参数  array('content'=>'test');
     * @param $method 请求方式 POST|GET
     * @param $multi 图片信息
     * @return string
     */
    private function api($command, $params = array(), $method = 'GET', $multi = false)
    {
		global $weibo2wp;
		
		$params['access_token']			 = $this->access_token;
		$params['oauth_consumer_key']	 = $weibo2wp->client_id;
		$params['openid']				 = $this->openid;
		$params['oauth_version']		 = '2.a';
		$params['clientip']				 = Common::getClientIp();
		$params['scope']				 = 'all';
		$params['appfrom']				 = 'php-sdk2.0beta';
		$params['seqid']				 = time();
		$params['serverip']				 = $_SERVER['SERVER_ADDR'];
		$params['format']				 = 'xml';

		$url = $this->apiUrlHttps . trim( $command, '/' );
        
		do{
			$r = $this->request( $url, $params, $method, $multi );
		}
		while( !isset( $r['root'] ) || !isset( $r['root']['ret'] ) || $r['root']['ret'] != 0 );
		
		$result = $r['root'];
		
		//check result
		if( isset($result['data']) )
		{
			return $result['data'];
		}
		else
		{
			$message = 'Weibo API Error. errcode: ' . $r['root']['errcode'] . '. msg: ' . $r['root']['msg'];
			$weibo2wp->add_error( $message );
			
			return array();
		}
    }
	
	function request( $url, $params, $method, $multi )
	{
		//请求接口
        $r = Http::request($url, $params, $method, $multi);
        $r = preg_replace('/[^\x20-\xff]*/', "", $r);
        $r = iconv("utf-8", "utf-8//ignore", $r);
		
		$r = xml_to_array($r);
		return $r;
	}

}