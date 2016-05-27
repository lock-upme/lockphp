<?php

/**
 * @desc Lock类
 */
class Lock {
	
	public $db;
	public $smarty;
	public $pagination;
	
	public function __construct() {
		require_once(D_ROOT.'lib/pagination/pagination.class.php');//引入分页
		require_once(D_ROOT.'lib/db/mysqli.class.php');//mysqli 驱动
		
		require_once(D_ROOT.'configs/db.php');
		$db = new DB($cc['dbuser'], $cc['dbpwd'], $cc['dbdatabase'],$cc['dbhost']);
		
		$smarty = new Smt;
		$pagination = new pagination();

		$this->smarty = $smarty;
		$this->db = $db;
		$this->pagination = $pagination;
	}
	
}

/**
 * @desc smarty
 */
class Smt extends Smarty {
	public $style;

	public function Smt() {
		if (empty($_SESSION['lang'])) { $lang = 'cn'; $_SESSION['lang'] = 'cn'; }
		else { $lang = $_SESSION['lang']; }
		$source = $_SERVER['REQUEST_URI'];

		$this->style = 'default';
		$this->Smarty();
		$this->template_dir = D_ROOT.'/media/themes/'.$this->style.'/';
		$this->compile_dir = D_ROOT.'/storage/'.$lang.'/templates_c/'.$this->style.'/';
		$this->config_dir = D_ROOT.'/storage/configs/'.$this->style.'/';
		$this->cache_dir = D_ROOT.'/storage/'.$lang.'/cache/'.$this->style.'/';

		switch($lang){
			case 'cn' :
				$this->config_load($this->config_dir.'zh-cn.lang');
				break;
			case 'en' :
				$this->config_load($this->config_dir.'en-us.lang');
				break;
			default:
				$this->config_load($this->config_dir.'zh-cn.lang');
				break;
		}

		//$this->cache_lifetime = 60 * 60; //设置缓存时间
		$this->left_delimiter = "{{";
		$this->right_delimiter = "}}";

		//$this->compile_check = true;
		//$this->debugging = true;
		//$this->caching = true;

		//注册函数
		//$this->register_function("smartAuthStatus",array('common','smartAuthStatus'));
		//$this->register_block("dynamic", array('common','smarty_block_dynamic'), false);
	}

}

/**
 * @desc route function
 */
function route( $urls ) {
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

