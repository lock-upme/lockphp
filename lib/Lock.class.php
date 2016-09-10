<?php

/**
 * @desc Lock类
 */
class Lock {
	
	public $db;
	public $smarty;
	public $pagination;
	
	public function __construct() {
		global $dbConfig;
		$smarty = new Smt;
		$pagination = new pagination();
		$this->smarty = $smarty;
		$this->db = new DB($dbConfig['dbuser'], $dbConfig['dbpwd'], $dbConfig['dbdatabase'], $dbConfig['dbhost']);
		$this->pagination = $pagination;
	}
	
	public function showmessage($msg, $url='') {
		$this->smarty ->assign('flush','1');
		$this->smarty ->assign('message',$msg);
		$this->smarty ->assign('url',$url);
		$this->smarty ->display('404.tpl');
		exit;
	}
		
}

/**
 * @desc smarty
 */
class Smt extends Smarty {
	public $style;

	public function __construct() {
		
		parent::__construct();
		
		if (empty($_SESSION['lang'])) { $lang = 'cn'; $_SESSION['lang'] = 'cn'; }
		else { $lang = $_SESSION['lang']; }
		$source = $_SERVER['REQUEST_URI'];

		$this->style = 'default';		
		$this->template_dir = D_ROOT.'/media/themes/'.$this->style.'/';
		$this->compile_dir = D_ROOT.'/storage/'.$lang.'/templates_c/'.$this->style.'/';
		$this->config_dir = D_ROOT.'/storage/configs/'.$this->style.'/';
		$this->cache_dir = D_ROOT.'/storage/'.$lang.'/cache/'.$this->style.'/';
		$configdir = $this->getConfigDir(0);
		
		switch($lang){
			case 'cn' :
				$this->configLoad($configdir.'zh-cn.lang');
				break;
			case 'en' :
				$this->configLoad($configdir.'en-us.lang');
				break;
			default:
				$this->configLoad($configdir.'zh-cn.lang');
				break;
		}

		//$this->cache_lifetime = 60 * 60; //设置缓存时间
		$this->left_delimiter = "{{";
		$this->right_delimiter = "}}";

		//$this->compile_check = true;
		//$this->debugging = true;
		//$this->caching = true;

		//注册函数
		$this->registerPlugin("function","smartyDict", array('Smt','smartyDict'));
		$this->registerPlugin("function","smartStrOverTime", array('Smt','smartStrOverTime'));
	}
	
	/**
	 * @desc 在模板中得到配置字典值
	 * @param array $param
	 * @return string
	 */
	public static function smartyDict($param) {
		$dict = G('dict');
		$res = $dict[$param['type']];
		if (is_numeric($param['id'])) {
			$tmp = ($param['type'] == 'area') ? $res[$param['id']]['name'] : $res[$param['id']];
			return $tmp ? $tmp : '无';
		} elseif (is_string($param['id'])) {
			$arr = explode(',', $param['id']);
			$str = '';
			if ($param['type'] == 'area'){
				foreach($arr as $id) {
					$str .= $res[$id]['name'].',';
				}
			}else{
				foreach($arr as $id) {
					$str .= $res[$id].',';
				}
			}
			return trim($str,',');
		} else {
			return $res;
		}
	}
	/**
	 * @desc 多久结束
	 * @param $time 时间戳
	 * @return string
	 */
	public static function smartStrOverTime($params) {
		$start = $params['start'];
		$end = $params['end'];
		$word = !empty($params['over']) ? '过期' :  '结束';
		if ($end < strtotime('now'))
			return '已'.$word;
		$time = $end-$start;
		if ($time < 60 * 60) {
			$str = '1小时内'.$word;
		} elseif ($time < 60 * 60 * 24) {
			$h = floor($time/(60*60));
			$str = '还有'.$h.'小时'.$word;
		} elseif ($time < 60 * 60 * 24 * 8) {
			$d = floor($time/(60*60*24));
			$str = '还有'.$d.'天'.$word;
		} else {
			$d = floor($time/(60*60*24));
			$str = '还有'.$d.'天'.$word;
		}
		return $str;
	}
}

/**
 * @desc route function
 */
function route($urls) {
	$path = $_SERVER['REQUEST_URI'];
	$path = ltrim($path, '/');
	foreach($urls as $pattern => $app) {
		if (preg_match($pattern, $path, $match)) {
			if (is_array($app)) {
				if (is_object($app[0])) {
					return call_user_func_array(array($app[0], $app[1]), $match);
				} else if (class_exists($app[0])) {
					$obj = new $app[0];
					return call_user_func_array(array($obj, $app[1]), $match);
				}
			} else if (function_exists($app)) {
				return call_user_func($app, $match);
			} else if (is_file($app)) {
				include( $app );
				break;
			}
		}
	}
	$htmlpath = htmlentities( $path );
	exit("抱歉啊，你访问的这个页面已经不在了");
}

