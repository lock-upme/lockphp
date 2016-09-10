<?php

	/**
	 * @desc 打印 
	 * @param unknown $msg
	 */
	function pr($msg) {
		echo "<pre>";
		print_r($msg);
	}

	/**
	 * @desc 隐藏IP
	 * @param string $ip
	 * @return string
	 */
	function hideIp($ip) {
		$iparr = explode('.', $ip);
		$iparr['3'] = '*';
		return implode('.', $iparr);
		//return preg_replace('/\.\d+$/', '.*', $ip);
	}
	
	/**
	 * @desc JSON解码
	 * @param json $joson
	 * @return array
	 */
	function jsondemsg($json) {
		if (empty($json)) { return false; }
		$res = json_decode($json, true);
		if ($res['status'] == 407){
			header('Location: /');
			exit();
		} else {
			return $res;
		}
	}

	/**
	 * @desc 返回信息
	 * @param array $msgarr
	 * @return string
	 */
	function jsonmsg($msgarr) {
	    if (PHP_VERSION < 5.3) {
	        $json = json_encode($msgarr);
	        $json = strtr($json, array('<' => '\u003C','>' => '\u003E'));
	    } else {
	        $json = json_encode($msgarr, JSON_HEX_TAG);
	    }
	    echo $json;
	    exit;
	}

	/**
	 * @desc 发送邮件
	 * @param string $email
	 * @param string $flag
     * @param array $msg['subject'],$msg['content']
	 * @return bool
	 */
	function sendMail($email, $flag, $username=false, $msg=false) {
		require_once './lib/mail/class.phpmailer.php';
		
        $subject = '';
        $emailbody = '';

		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth   = true;
		$mail->SMTPSecure = "";
		
		$mail->Encoding  = "base64";
		$mail->Host       = "smtp address";
		$mail->Port       = 27;
		$mail->CharSet    = "utf-8";
		
		$mail->Username   = "username";
		$mail->Password   = "password";
		$mail->From       = "send meail";		
		
		$mail->FromName   = "name";
		$mail->Subject    = $subject;
		$mail->Body       = $emailbody;
		$mail->AddAddress($email,$email);

		$mail->IsHTML(true);
		if (!$mail->Send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
			return false;
		} else {
			return true;
		}
		$mail->ClearAddresses();
		$mail->ClearAttachments();
	}

	/**
	 * @desc 文件大小格式化
	 * @param int $size
	 * @return int
	 */
	function printSize($size) {
		//return $size / 1024;
		//return (($size /= 1024) >= 1024) ? round($size / 1024, 1). 'MB' : round($size, 1). 'KB';
		$units = array(' B', ' KB', ' MB', ' GB', ' TB');
		for ($i = 0; $size >= 1024 && $i < 4; $i++) $size /= 1024;
		return round($size, 2).$units[$i];
	}

	/**
	 * @desc 裁剪图片
	 * @param string $src
	 * @param string $thumb
	 * @param string $crop
	 * @param string $mode
	 * @param string $opts
	 * @param number $quality
	 * @return string
	 */
	function cropThumb($src, $thumb, $crop = '150x150', $mode = 'common', $opts = '', $quality = 100) {		
		$argstr = "+profile '*' -colorspace RGB -quality $quality";
		$cmd = GM. " convert $src ";
		if ($mode == 'auto') {
			$cmd .= " -thumbnail $crop^ -gravity center -extent $crop $argstr $thumb";
		} else if ($mode == 'common') {
			$cmd .= " -thumbnail $crop -background white -gravity center -extent $crop $thumb";
		} else if ($mode == 'base') {
			list($width, $height) = getimagesize($src);
			$cmd .= " -thumbnail {$width}x{$height} -background white -gravity center -extent $crop $thumb";
		} else if ($mode == 'crop') {
			$cmd .= " -crop $opts -thumbnail $crop $argstr $thumb";
		} else if ($mode == 'watermark') {
			$cmd .= " -thumbnail '600x>' $argstr $thumb && ". GM. " composite -dissolve 75 -gravity SouthEast -geometry '+10+10' -quality $quality ". DISCUZ_ROOT. "/watermark.png $thumb $thumb";
		}
		//echo $cmd."\n\r";
		//$cmd = escapeshellcmd($cmd);

		return shell_exec($cmd);
	}

	/**
	 * @desc 分割标签
	 * @param string $words
	 * @return string
	 */
	function splitTags($words) {
		if (empty($words)) { return false; }
		$words = preg_replace('/(\s|，|,|、|。|；|;|-)+/', ',', $words);
		$words = trim($words, ', ');
		return $words;
	}

    /**
     * @desc 得到IP地址
     * @return string
     */
	function getIp() {
	    if (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown'))
	        $ip = getenv('REMOTE_ADDR');
	    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
	        $ip = $_SERVER['REMOTE_ADDR'];
	    else if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown'))
	        $ip = getenv('HTTP_CLIENT_IP');
	    else if (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown'))
	        $ip = getenv('HTTP_X_FORWARDED_FOR');
	    $tmpip = filter_var($ip, FILTER_VALIDATE_IP);
	    $ip = $tmpip ? $tmpip : 'unknown';
	    return $ip;
	}

    /**
	  * @desc 得到邮箱域名后缀
	  * @param string $email
	  * @return string
	  */
	 function mailDomainName($email){
			preg_match('/@([a-zA-Z\-0-9]+)./i',$email, $matches);
			$domain = $matches[1];
			switch ($domain) {
				case 'sina': $dname = '新浪'; break;
				case '163':
				case '126':
				case 'yeah': $dname = '网易'; break;
				case 'tom': $dname = 'Tom'; break;
				case 'qq': $dname = 'QQ'; break;
				case 'hotmail': $dname = 'Hotmail'; break;
				case 'gmail': $dname = '谷哥'; break;
				case 'sohu': $dname = '搜狐'; break;
				case 'yahoo': $dname = '雅虎'; break;
				case '21cn': $dname = '21CN'; break;
				default: $dname = $domain;break;
			}
			return $dname;
	}
	function getMailDomain($email){
			$arr = explode('@',$email);
			$dname = 'mail.'.$arr[1];
			return $dname;
	}

    /**
     * @desc 得到随机串
     * @param int $length
     * @return string
     */
    function getRand($length){
		$output='';
		$pattern = '1234567890ABCDEFGHIJKLOMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-_=!';
		for($i=0;$i<$length;$i++){
			$output .= $pattern{mt_rand(0,35)};
		}
		return $output;
    }
	/**
	 * @desc 编码 DISCUZ
	 */
	function setAuthCode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	    $ckey_length = 4;

	    $key = md5($key ? $key : KEY);
	    $keya = md5(substr($key, 0, 16));
	    $keyb = md5(substr($key, 16, 16));
	    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

	    $cryptkey = $keya.md5($keya.$keyc);
	    $key_length = strlen($cryptkey);

	    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	    $string_length = strlen($string);

	    $result = '';
	    $box = range(0, 255);

	    $rndkey = array();
	    for($i = 0; $i <= 255; $i++) {
	        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
	    }

	    for($j = $i = 0; $i < 256; $i++) {
	        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
	        $tmp = $box[$i];
	        $box[$i] = $box[$j];
	        $box[$j] = $tmp;
	    }

	    for($a = $j = $i = 0; $i < $string_length; $i++) {
	        $a = ($a + 1) % 256;
	        $j = ($j + $box[$a]) % 256;
	        $tmp = $box[$a];
	        $box[$a] = $box[$j];
	        $box[$j] = $tmp;
	        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	    }

	    if($operation == 'DECODE') {
	        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
	        	return substr($result, 26);
	        } else {
	            return '';
	        }
	    } else {
	        return $keyc.str_replace('=', '', base64_encode($result));
	    }
	}

	/**
     * @desc 检测邮箱
     * @param string $parameter
     * @return bool
     */
  	function checkEmail($email){
  		if (empty($email)) { return false; }
  		$pattern = "/^[0-9a-zA-Z_.-]+@(([0-9a-zA-Z-_]+)[.])+[a-z]{2,5}$/i";
  		if (preg_match($pattern, $email)) {
  			return true;
  		} else {
  			return false;
  		} 
    }

	/**
	 * @desc 检测远程文件是否存在
	 */
	function checkRemoteFile($url) {
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		$result = curl_exec($curl);
		$found = false;
		if ($result !== false) {
			$statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			$found = ($statusCode == 200) ? true : false;
		}
		curl_close($curl);
		return $found;
	}

    /**
     * @desc 替换字符串中间位置为星号
     * @param $string
	 * replaceString('simple', '***', 1, 3,"utf-8");
     */
	function replaceString($string, $replacement, $start, $length=null, $encoding = null) {
		if ($encoding == null) {
			if ($length == null) {
				return mb_substr($string,0,$start).$replacement;
			} else {
				return mb_substr($string,0,$start).$replacement.mb_substr($string,$start + $length);
			}
		} else {
			if ($length == null) {
				return mb_substr($string,0,$start,$encoding).$replacement;
			} else {
				return mb_substr($string,0,$start,$encoding). $replacement. mb_substr($string,$start + $length,mb_strlen($string,$encoding),$encoding);
			}
		}
	}

 	/**
    * @desc 中文截取，支持gb2312,gbk,utf-8,big5
    *
    * @param string $str 要截取的字串
    * @param int $start 截取起始位置
    * @param int $length 截取长度
    * @param string $charset utf-8|gb2312|gbk|big5 编码
    * @param $suffix 是否加尾缀
    */
    function cutString($str, $start=0, $length, $charset="utf-8", $suffix=true) {
        if (function_exists("mb_substr")) {
            if(mb_strlen($str, $charset) <= $length) return $str;
            $slice = mb_substr($str, $start, $length, $charset);
        } else {
            $re['utf-8']  = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            if (count($match[0]) <= $length) return $str;
            $slice = join("",array_slice($match[0], $start, $length));
        }
        if (!$suffix) return $slice."...";
        return $slice;
    }
    
    /**
     * @desc 获取用户操作系统
     * @return string
     */
    function getUserOS() {
        $user_OSagent = $_SERVER['HTTP_USER_AGENT'];
        if(strpos($user_OSagent,"NT 5.1")) {
            $visitor_os ="Windows XP";
        } elseif(strpos($user_OSagent,"NT 5.2") && strpos($user_OSagent,"WOW64")){
            $visitor_os ="Windows XP 64-bit Edition";
        } elseif(strpos($user_OSagent,"NT 5.2")) {
            $visitor_os ="Windows 2003";
        } elseif(strpos($user_OSagent,"NT 6.0")) {
            $visitor_os ="Windows Vista";
        } elseif(strpos($user_OSagent,"NT 6.1")) {
            $visitor_os ="Windows 7";
        } elseif(strpos($user_OSagent,"NT 6.2")) {
            $visitor_os ="Windows 8";
        } elseif(strpos($user_OSagent,"NT 5.0")) {
            $visitor_os ="Windows 2000";
        } elseif(strpos($user_OSagent,"4.9")) {
            $visitor_os ="Windows ME";
        } elseif(strpos($user_OSagent,"NT 4")) {
            $visitor_os ="Windows NT 4.0";
        } elseif(strpos($user_OSagent,"98")) {
            $visitor_os ="Windows 98";
        } elseif(strpos($user_OSagent,"95")) {
            $visitor_os ="Windows 95";
        } elseif(strpos($user_OSagent,"Mac")) {
            $visitor_os ="Mac";
        } elseif(strpos($user_OSagent,"Linux")) {
            $visitor_os ="Linux";
        } elseif(strpos($user_OSagent,"Unix")) {
            $visitor_os ="Unix";
        } elseif(strpos($user_OSagent,"FreeBSD")) {
            $visitor_os ="FreeBSD";
        } elseif(strpos($user_OSagent,"SunOS")) {
            $visitor_os ="SunOS";
        } elseif(strpos($user_OSagent,"BeOS")) {
            $visitor_os ="BeOS";
        } elseif(strpos($user_OSagent,"OS/2")) {
            $visitor_os ="OS/2";
        } elseif(strpos($user_OSagent,"PC")) {
            $visitor_os ="Macintosh";
        } elseif(strpos($user_OSagent,"AIX")) {
            $visitor_os ="AIX";
        } elseif(strpos($user_OSagent,"IBM OS/2")) {
            $visitor_os ="IBM OS/2";
        } elseif(strpos($user_OSagent,"BSD")) {
            $visitor_os ="BSD";
        } elseif(strpos($user_OSagent,"NetBSD")) {
            $visitor_os ="NetBSD";
        } else {
            $visitor_os ="其它";
        }
        return $visitor_os;
    }
    /**
     * @desc 获取用户浏览器
     * @return string
     */
    function getUserBrowser() {
        $sys = $_SERVER['HTTP_USER_AGENT'];
        if (stripos($sys, "NetCaptor") > 0) {
            $exp[0] = "NetCaptor";
            $exp[1] = "";
        } elseif (stripos($sys, "Firefox/") > 0) {
            preg_match("/Firefox\/([^;)]+)+/i", $sys, $b);
            $exp[0] = "Mozilla Firefox";
            $exp[1] = $b[1];
        } elseif (stripos($sys, "MAXTHON") > 0) {
            preg_match("/MAXTHON\s+([^;)]+)+/i", $sys, $b);
            preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);
            $exp[0] = $b[0] . " (IE" . $ie[1] . ")";
            $exp[1] = $ie[1];
        } elseif (stripos($sys, "MSIE") > 0) {
            preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);
            $exp[0] = "Internet Explorer";
            $exp[1] = $ie[1];
        } elseif (stripos($sys, "Netscape") > 0) {
            $exp[0] = "Netscape";
            $exp[1] = "";
        } elseif (stripos($sys, "Opera") > 0) {
            $exp[0] = "Opera";
            $exp[1] = "";
        } elseif (stripos($sys, "Chrome") > 0) {
            $exp[0] = "Chrome";
            $exp[1] = "";
        } else {
            $exp = "未知浏览器";
            $exp[1] = "";
        }
        return $exp;
    } 
    
    /**
     * @desc 得到首字母大写
     */    
    function getFirstChar($s0) {
    	$firstchar_ord = ord(strtoupper($s0{0}));
    	if (($firstchar_ord>=65 and $firstchar_ord<=91) or ($firstchar_ord>=48 and $firstchar_ord<=57)) return strtoupper($s0{0});
    	$s=iconv("UTF-8","gb2312", $s0);
    	$asc=ord($s{0})*256+ord($s{1})-65536;
    	if($asc>=-20319 and $asc<=-20284)return "A";
    	if($asc>=-20283 and $asc<=-19776)return "B";
    	if($asc>=-19775 and $asc<=-19219)return "C";
    	if($asc>=-19218 and $asc<=-18711)return "D";
    	if($asc>=-18710 and $asc<=-18527)return "E";
    	if($asc>=-18526 and $asc<=-18240)return "F";
    	if($asc>=-18239 and $asc<=-17923)return "G";
    	if($asc>=-17922 and $asc<=-17418)return "H";
    	if($asc>=-17417 and $asc<=-16475)return "J";
    	if($asc>=-16474 and $asc<=-16213)return "K";
    	if($asc>=-16212 and $asc<=-15641)return "L";
    	if($asc>=-15640 and $asc<=-15166)return "M";
    	if($asc>=-15165 and $asc<=-14923)return "N";
    	if($asc>=-14922 and $asc<=-14915)return "O";
    	if($asc>=-14914 and $asc<=-14631)return "P";
    	if($asc>=-14630 and $asc<=-14150)return "Q";
    	if($asc>=-14149 and $asc<=-14091)return "R";
    	if($asc>=-14090 and $asc<=-13319)return "S";
    	if($asc>=-13318 and $asc<=-12839)return "T";
    	if($asc>=-12838 and $asc<=-12557)return "W";
    	if($asc>=-12556 and $asc<=-11848)return "X";
    	if($asc>=-11847 and $asc<=-11056)return "Y";
    	if($asc>=-11055 and $asc<=-10247)return "Z";
    	return null;
    }
   
    
   /**
    * @desc 获取页面内容方法一、file_get_contents
    */
    function getFileByGetContent($url) {
        //只读2字节  如果为(16进制)1f 8b (10进制)31 139则开启了gzip ; 
        $file = @fopen($url, "rb"); 
        $bin = @fread($file, 2);  
        @fclose($file);
        $strInfo = @unpack("C2chars", $bin);   
        $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);   
        $url = ($typeCode==31139) ? "compress.zlib://".$url:$url; // 三元表达式         
        return @file_get_contents($url);
    }
    
    function base64url_decode($data) {
    	return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
    
    function writeFileContent($filename, $data) {
    	$fp=fopen($filename,"w");
    	fputs($fp,$data);
    	fclose($fp);
    	return true;
    	//$res = file_put_contents ($filename , $data);
    	//return $res;
    }
    function getFileContent($filename) {
    	return file_get_contents ($filename);
    }
    
    /**  
     * @desc 对数据进行编码转换  
     * @param array/string $data       数组  
     * @param string $output    转换后的编码  
     * @return 返回编码后的数据
     */  
    function array_iconv($data,  $output = 'utf-8') {  
        $encode_arr = array('UTF-8','ASCII','GBK','GB2312','BIG5','JIS','eucjp-win','sjis-win','EUC-JP');  
        $encoded = mb_detect_encoding($data, $encode_arr);  
      
        if(empty($encoded)) $encoded='UTF-8';        
        if (!is_array($data)) {  
            return @mb_convert_encoding($data, $output, $encoded);  
        } else {  
            foreach ($data as $key=>$val) { 
                $key = array_iconv($key, $output);  
                if (is_array($val)) {  
                    $data[$key] = array_iconv($val, $output);  
                } else {  
                $data[$key] = @mb_convert_encoding($data, $output, $encoded);  
                }  
            }  
        	return $data;  
        }  
    }
    
    /**
     * @desc 随机手机
     */
    function randMobile() {
        $arr = array(139,138,137,136,135,134,159,158,150,151,152,157,188,187,130,131,132,155,156,133,153,189,180);
        $head =  $arr[array_rand($arr)];
        $chars_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        $charsLen = count($chars_array) - 1;
        $outputstr = "";
        for ($i=0; $i<8; $i++) {
            $outputstr .= $chars_array[mt_rand(0, $charsLen)];
        }
        return $head.$outputstr;
    }
    
    /**
     * @desc 是否在微信浏览器中打开
     * @return boolean
     */
    function checkWeixinBrowser() {
    	if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
    		return true;
    	}
    	return false;
    }
    
    /**
     * @desc 得到手机系统
     * @return string
     */
    function checkPhoneOs() {
    	if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone')||strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')) {
    		return 'ios';
    	} else if (strpos($_SERVER['HTTP_USER_AGENT'], 'Android')) {
    		return 'android';
    	}
    }
    
    /**
     * @desc 是否为手机
     * @return bool
     */
    function checkWap() {
    	if (stristr($_SERVER['HTTP_VIA'],"wap")) {
    		return true;
    	} elseif(strpos(strtoupper($_SERVER['HTTP_ACCEPT']),"VND.WAP.WML") > 0){
    		return true;
    	} elseif(preg_match('/(blackberry|configuration\/cldc|hp |hp-|htc |htc_|htc-|iemobile|kindle|midp|mmp|motorola|mobile|nokia|opera mini|opera |Googlebot-Mobile|YahooSeeker\/M1A1-R2D2|android|iphone|ipod|mobi|palm|palmos|pocket|portalmmm|ppc;|smartphone|sonyericsson|sqh|spv|symbian|treo|up.browser|up.link|vodafone|windows ce|xda |xda_)/i', $_SERVER['HTTP_USER_AGENT'])){
    		return true;
    	} else{
    		return false;
    	}
    }
    
    /**
     * @desc 给图片加域名
     * @param $html
     * @param $domain
     * @return mixed
     */
    function imageAddDomain($html, $domain) {
        $preg="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/";
        preg_match_all($preg,$html,$matches);
        for ($i=0; $i< count($matches[0]); $i++) {
            $pattern="/src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"]/";
            preg_match($pattern,$matches[0][$i],$match);
            $html = str_replace($match[0],'src="'.$domain.$match[1].'"',$html);
        }
        return $html;
    }       
    
    /**
     * @desc 获取全局配置文件
     */
    function G($key) {
    	if (empty($key)) { return false; }
    	require(D_ROOT.'configs/global.php');
		if (strpos($key, '.') === false) {
			return isset($_GLOBAL[$key]) ? $_GLOBAL[$key] : '';
		} else {
			$keyarr = explode('.',$key);
			return isset($_GLOBAL[$keyarr[0]][$keyarr[1]]) ? $_GLOBAL[$keyarr[0]][$keyarr[1]] : '';
		}
    }
    
    /**
     * @desc 切换数据库
     * @param string $module
     * @param bool $prename
     * @return array(db, prename, dbname)
     */
    function switchDB($module, $prename=true) {
    	require(D_ROOT.'configs/db.php');    	
    	$dbArr = $dbGroup[$module];
    	
    	if (!isset($dbArr)) {
    		return;
    	}
    	$db = new DB($dbArr['user'], $dbArr['pwd'], $dbArr['name'], $dbArr['host'], $dbArr['port']);
    	return $prename ? array($db, $dbArr['name'].'_', $dbArr['name']) : $db;    	
    }
    
    /**
     * @desc 表前缀
     * @param string $module
     * @return table 
     */
    function getTablePrename($module='users') {
    	require(D_ROOT.'configs/db.php');
    	$dbArr = $dbGroup[$module];
    	
    	if (!isset($dbArr)) {
    		return;
    	}
    	return ($dbArr['name']) ? $dbArr['name'].'_' : false;
    }    
    
	/**
	 * @desc 库前缀
	 * @param string $module
	 * @return table
	 */
	function getDbName($module='users') {
		require(D_ROOT.'configs/db.php');
		$dbArr = $dbGroup[$module];

		if (!isset($dbArr)) {
			return false;
		}
		return !empty($dbArr['name']) ? $dbArr['name'] : false;
	}
	
	/**
	 * 系统用户cookie信息
	 */
	function userBoardCookie() {
		$cookeArr = G('cookie');
		$key = $cookeArr['boardname'];
		if (!isset($_COOKIE[$key])) {
			return false;
		}
		$ckarr = explode("\t", addslashes(setAuthCode($_COOKIE[$key], 'DECODE', KEY)));
		list($uid, $username, $status, $email, $rule) = $ckarr;
		 
		$arr = array('uid' => $uid, 'username' => $username, 'status' => $status, 'email' => $email, 'rule' => $rule);
		return $arr;
	}
	
	/**
	 * @desc 发送文件流
	 * @param string $url
	 * @param binary $file
	 * @return string
	 */
	function sendStreamFile($url, $file) {
		if (empty($url) || empty($file)) { return false; }		
		$opts = array(
				'http' => array(
						'method' => 'POST',
						'header' => 'content-type:application/x-www-form-urlencoded',
						'content' => $file
				)
		);
		$context = stream_context_create($opts);
		$response = file_get_contents($url, false, $context);
		return $response;
	}

	/**
	 * @desc 取得一级数据
	 * @param $tab
	 * @param $id
	 * @return array
	 */
	function getDictList($tab, $id = false) {
		$dict = G('dict.'.$tab);

		$tid = 'upid';
		if ($tab == 'school') { $tid = 'cid'; }
		elseif ($tab == 'department') { $tid = 'sid'; }
		$dictarr = array();
		foreach ($dict as $key => $val) {
			$dictarr[$val[$tid]][$key] = $val;
		}
		return ($id === false) ? $dictarr : !empty($dictarr[$id]) ? $dictarr[$id] : '';
	}
	
	/**
	 * @desc 取得具体值
	 * @param $tab
	 * @param $id
	 * @return array
	 */
	function getDictVal($tab, $id) {
		if (!$tab || !$id)  return false;
		$dict = G('dict.'.$tab);
		if (!$dict)  return false;
		return $dict[$id];
	}
	
	/**
	 * @desc 日期格式验证 支持的格式为“YYYY-MM-DD HH:mm:ss”和“YYYY-MM-DD”
	 * @param $datetime
	 * @return bool
	 */
	function checkDateTime($datetime){
		if (!$datetime){ return false;}
		if (preg_match("/^((\\d{2}(([02468][048])|([13579][26]))[\\-\\/\\s]?((((0?[13578])|(1[02]))[\\-\\/\\s]?((0?[1-9])|([1-2][0-9])|(3[01])))|(((0?[469])|(11))[\\-\\/\\s]?((0?[1-9])|([1-2][0-9])|(30)))|(0?2[\\-\\/\\s]?((0?[1-9])|([1-2][0-9])))))|(\\d{2}(([02468][1235679])|([13579][01345789]))[\\-\\/\\s]?((((0?[13578])|(1[02]))[\\-\\/\\s]?((0?[1-9])|([1-2][0-9])|(3[01])))|(((0?[469])|(11))[\\-\\/\\s]?((0?[1-9])|([1-2][0-9])|(30)))|(0?2[\\-\\/\\s]?((0?[1-9])|(1[0-9])|(2[0-8]))))))(\\s(((0?[0-9])|([1][0-9])|([2][0-4]))\\:([0-5]?[0-9])((\\s)|(\\:([0-5]?[0-9])))))?$/", $datetime)){
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * @desc 发送值到redis
	 * @param string $key
	 * @param string $value
	 * @return boolean
	 */
	function sendRedisPublish($key, $value) {
		if (empty($key) || empty($value)) { return false; }
		$redisArr = G('redis');
		$redis = new Redis();
		$redis->connect($redisArr['host'], $redisArr['port']);
		$redis->publish($key, $value);//lpush
		unset($redis);
		return true;
	}
	
	/**
	 * @desc 加密与解密,与接口数据互解
	 * @param string $str
	 * @param string $operate
	 * @return string
	 */
	function des($str, $operate='DECODE') {
		if (empty($str)) { return false; }
		require_once D_ROOT.'/lib/encryption/DES.class.php';
		$des = new DES();
		if ($operate == 'DECODE') {
			$res = $des->decrypt($str);
		} else {
			$res = $des->encrypt($str);
		}
		unset($des);
		return $res;
	}
	/**
	 * @desc 加密与解密,与接口数据互解
	 * @param string $str
	 * @param string $operate
	 * @return string
	 */
	function aes($str, $operate='DECODE') {
		if (empty($str)) { return false; }
		require_once D_ROOT.'/lib/encryption/AES.class.php';
		$aes = new AES();
		if ($operate == 'DECODE') {
			$res = $aes->decrypt($str);
		} else {
			$res = $aes->encrypt($str);
		}
		unset($aes);
		return $res;
	}
	
	/**
	 * @desc 发送文件流
	 * @param string $url
	 * @param binary $file
	 * @return string
	 */
	function apiCallFile($file, $url) {
		if (empty($url) || empty($file)) { return false; }
		$opts = array(
				'http' => array(
						'method' => 'POST',
						'header' => 'content-type:application/x-www-form-urlencoded',
						'content' => $file
				)
		);
		$context = stream_context_create($opts);
		$response = file_get_contents(API_URL.$url, false, $context);
		return $response;
	}
	
	function _post($key, $filter='string') {
		if ($filter == 'int') {
			$value = intval(addslashes(trim(filter_input(INPUT_POST, $key, FILTER_VALIDATE_INT))));
		} else {
			$value = addslashes(trim(filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING)));
		}
		return $value;
	}
	
	function _get($key, $filter='string') {
		if ($filter == 'int') {
			$value = intval(addslashes(trim(filter_input(INPUT_GET, $key, FILTER_VALIDATE_INT))));
		} else {
			$value = addslashes(trim(filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING)));
		}
		return $value;
	}	
	
	/**
	 * @desc 是否在微信浏览器中打开
	 * @return boolean
	 */
	function is_weixin() {
		if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false ) {
			return true;
		}
		return false;
	}
	function prepareJSON($input){
		$input = mb_convert_encoding($input,'UTF-8','ASCII,UTF-8,ISO-8859-1');
		var_dump($input);die;
		if(substr($input,0,3) == pack("CCC",0xEF,0xBB,0xBF)) $input = substr($input,3);

		return $input;
	}
	/**
	 * @desc 得到字符串长度
	 * @param string $str
	 * @return int
	 */
	function getStrlen($str) {
		$i = 0;
		$count = 0;
		$len = strlen($str);
		while ($i < $len) {
			$chr = ord ($str[$i]);
			$count++;
			$i++;
			if($i >= $len) { break; }
			if($chr & 0x80) {
				$chr <<= 1;
				while ($chr & 0x80) {
					$i++;
					$chr <<= 1;
				}
			}
		}
		return $count;
	}
	/**
	 * @desc 获取年份
	 * @param int $num
	 * @return array
	 */
	function getYear($addyear=0, $num=50) {
		$nowy = date("Y")+$addyear;
		$arr = array();
		for ($i=0; $i<$num; $i++) {
			$they = $nowy - $i;
			$arr[$they] = $they;
		}
		return $arr;
	}
	
	/**
	 * @desc 获取月份
	 * @return array
	 */
	function getMonth() {
		$arr = array();
		for ($i=1; $i<=12; $i++) {
			$arr[$i] = $i;
		}
		return $arr;
	}
	
	/**
	 * @desc 计算日期差 年月
	 */
	function getYearMonth($params) {
		$start = strtotime($params['start']) ? strtotime($params['start']) : $params['start'];
		$end = $params['end'] ? (strtotime($params['end']) ? strtotime($params['end']) : $params['end']) : time();

		$timetemp = abs($end - $start);
		$y = floor($timetemp / (3600*24*360));
		$string = '';
		if ($y) {
			$timetemp -= $y * 3600 * 24 * 360;
			$string .= $y.'年';
		}
		$m = floor($timetemp / (3600*24*30));
		if ($m > 0) {
			$string .= $m.'个月';
		}
		return $string;
	}