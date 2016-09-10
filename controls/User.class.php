<?php
/**
 * @desc 用户模块
 */
class User extends Lock{
	
	public function __construct(){
		parent::__construct();		
	}
	
	/**
	 * @desc 用户登录
	 */
	public function Page_Login() {		
		$this->smarty->display('users/login.tpl');
	}
	
}

?>