<?php


function get_weibo( $openid )
{
	return new Weibo2wp_Weibo( $openid );
}

function weibo2wp_synch_dailly() {
	global $weibo2wp;
	
	set_time_limit(0);

	$auth_list = $weibo2wp->get_auth_list();

	if( empty( $auth_list ) )
		return false;
	
	foreach( $auth_list as $val )
	{
		$weibo = get_weibo( $val['openid'] );
		$weibo->synch();
	}
	
	return true;
}


function weibo_motion_2_img( $str )
{
	$motion_str = "惊讶,撇嘴,色,发呆,得意,流泪,害羞,闭嘴,睡,大哭,尴尬,发怒,调皮,呲牙,微笑,难过,酷,非典,抓狂,吐,偷笑,可爱,白眼,傲慢,饥饿,困,惊恐,流汗,憨笑,大兵,奋斗,咒骂,疑问,嘘...,晕,折磨,衰,骷髅,敲打,再见,闪人,发抖,爱情,跳跳,找,美眉,猪头,小狗,钱,拥抱,灯泡,酒杯,音乐,蛋糕,闪电,炸弹,刀,足球,猫咪,便便,咖啡,饭,女,玫瑰,凋谢,男,爱心,心碎,药丸,礼物,吻,会议,电话,时间,太阳,月亮,强,弱,握手,胜利,邮件,电视,多多,美女,汉良,飞吻,怄火,毛毛,Q仔,西瓜,白酒,汽水,下雨,多云,雪人,星星,冷汗,擦汗,抠鼻,鼓掌,糗大了,坏笑,左哼哼,右哼哼,哈欠,鄙视,委屈,快哭了,阴险,亲亲,吓,可怜,菜刀,啤酒,篮球,乒乓,示爱,瓢虫,抱拳,勾引,拳头,差劲,爱你,NO,OK,转圈,磕头,回头,跳绳,挥手,激动,街舞,献吻,左太极,右太极,喜糖,红包";

	$motions = explode(',', $motion_str);
	$count_count = count( $motions);
	
	for ( $i = 0; $i < $count_count; $i++ )
	{
		$motion = $motions[$i];
		
		$ii = $i;
		if ($ii === 135) {
			$ii = 150;
		} else if ($ii === 136) {
			$ii = 151;
		}
		
		if ( strpos($str, "/" . $motion) != false )
		{
			$str = str_replace("[/" . $motion . "]", "<img src=\"http://mat1.gtimg.com/www/mb/images/face/" . $ii . ".gif\" title=\"" . $motion . "\" class=\"weibo_emotion\"/>", $str);
			$str = str_replace("/" . $motion, "<img src=\"http://mat1.gtimg.com/www/mb/images/face/" . $ii . ".gif\" title=\"" . $motion . "\" class=\"weibo_emotion\"/>", $str);
		}
	}
	
	//Check for [em]e327775[/em]
	preg_match_all('/\[em\](.*?)\[\/em\]/i', $str, $emoji_array);
	foreach($emoji_array[0] as $key => $val) {
		$str = str_replace($val, '<img src="http://qzonestyle.gtimg.cn/qzone/em/'.$emoji_array[1][$key].'.gif">', $str);
	}
	
	return $str;
}

/**
 * convert xml to array
 * 
 * @param string $xml_string
 * @param int $get_attributes
 * @param string $priority
 * @access public
 * @return array
 */
function xml_to_array( $xml_string, $get_attributes = 1, $priority = 'tag' )
{
	if( !$xml_string ) return array(); 

	if( !function_exists( 'xml_parser_create' ) ) { 
		return array(); 
	}
	
	//Get the XML parser of PHP - PHP must have this module for the parser to work 
	$parser = xml_parser_create(''); 
	xml_parser_set_option( $parser, XML_OPTION_TARGET_ENCODING, 'UTF-8' ); 
	xml_parser_set_option( $parser, XML_OPTION_CASE_FOLDING, 0 ); 
	xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 1 ); 
	xml_parse_into_struct( $parser, trim( $xml_string), $xml_values ); 
	xml_parser_free( $parser ); 
	
	if( !$xml_values ) return;//Hmm... 

	//Initializations 
	$xml_array = array(); 
	$parents = array(); 
	$opened_tags = array(); 
	$arr = array(); 

	$current = &$xml_array; //Refference 

	//Go through the tags. 
	$repeated_tag_index = array();//Multiple tags with same name will be turned into an array 
	foreach( $xml_values as $data )
	{
		unset( $attributes, $value );//Remove existing values, or there will be trouble 

		//This command will extract these variables into the foreach scope 
		// tag(string), type(string), level(int), attributes(array). 
		extract( $data );//We could use the array by itself, but this cooler. 

		$result = array(); 
		$attributes_data = array(); 

		if( isset( $value ) ) { 
			if( $priority == 'tag' ) $result = $value; 
			else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode 
		} 

		//Set the attributes too. 
		if( isset( $attributes ) and $get_attributes ) { 
			foreach( $attributes as $attr => $val ) { 
				if( $priority == 'tag' ) $attributes_data[$attr] = $val; 
				else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr' 
			} 
		} 

		//See tag status and do the needed. 
		if( $type == "open" ) 
		{ //The starting of the tag '<tag>' 
			$parent[$level-1] = &$current; 
			if(!is_array($current) or (!in_array($tag, array_keys($current)))) //Insert New tag 
			{
				$current[$tag] = $result; 
				if( $attributes_data ) 
					$current[$tag. '_attr'] = $attributes_data; 
				$repeated_tag_index[$tag.'_'.$level] = 1; 

				$current = &$current[$tag]; 

			} else { //There was another element with the same tag name 

				if( isset( $current[$tag][0] ) ) 
				{
					//If there is a 0th element it is already an array 
					$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result; 
					$repeated_tag_index[$tag.'_'.$level]++; 
				} else {//This section will make the value an array if multiple tags with the same name appear together 
					$current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
					$repeated_tag_index[$tag.'_'.$level] = 2; 

					if(isset($current[$tag.'_attr'])) 
					{ 
						//The attribute of the last(0th) tag must be moved as well 
						$current[$tag]['0_attr'] = $current[$tag.'_attr']; 
						unset( $current[$tag.'_attr'] ); 
					} 

				} 
				$last_item_index = $repeated_tag_index[$tag.'_'.$level]-1; 
				$current = &$current[$tag][$last_item_index]; 
			} 

		} elseif( $type == 'complete' ) 
		{ 
			//Tags that ends in 1 line '<tag />' 
			//See if the key is already taken. 
			if( !isset( $current[$tag] ) ) 
			{ 	
				//New Key 
				$current[$tag] = $result; 
				$repeated_tag_index[$tag.'_'.$level] = 1; 
				if( $priority == 'tag' and $attributes_data ) 
					$current[$tag. '_attr'] = $attributes_data; 

			} else { //If taken, put all things inside a list(array) 
				if( isset( $current[$tag][0] ) and is_array( $current[$tag] ) ) 
				{
					//If it is already an array... 

					// ...push the new element into that array. 
					$current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result; 

					if($priority == 'tag' and $get_attributes and $attributes_data) 
					{ 
						$current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data; 
					} 
					$repeated_tag_index[$tag.'_'.$level]++; 

				} else { //If it is not an array... 
					$current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
					$repeated_tag_index[$tag.'_'.$level] = 1; 
					if( $priority == 'tag' and $get_attributes ) { 
						if( isset( $current[$tag.'_attr'] ) ) //The attribute of the last(0th) tag must be moved as well
						{  
							$current[$tag]['0_attr'] = $current[$tag.'_attr']; 
							unset( $current[$tag.'_attr'] ); 
						} 

						if( $attributes_data ) 
						{ 
							$current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data; 
						} 
					} 
					$repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken 
				} 
			} 

		} elseif( $type == 'close' ) { //End of tag '</tag>' 
			$current = &$parent[$level-1]; 
		} 
	} 
	return $xml_array; 
}