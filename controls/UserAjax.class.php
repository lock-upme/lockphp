<?php
/**
 * @desc 用户模块处理
 */
class UserAjax extends Lock{	
	
	public function __construct(){
		parent::__construct();		
	}
	
	/**
	 * @desc 用户登录
	 */
	public function login() {		
		$phone = _post('phone');
		if (empty($phone)) {
			jsonmsg(array('code' => 0, 'message' => '请填写手机号'));
		}
		
		$password = _post('password');
		if (empty($password)) {
			jsonmsg(array('code' => 0, 'message' => '请填写手机号'));
		}
		
		
		//proc...
		jsonmsg(array('code' => 1, 'message' => '登录成功'));
	}
}

?>