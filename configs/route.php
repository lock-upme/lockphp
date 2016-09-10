<?php
/**
 * 路由文件
 * 规则 '!正则表达式!'
 * 如:
 * '!^$!' => array('类名', '方法'),
 * '!^search(/|\?.*|)$!i' => array('类名', '方法'), http://www.domain.com/search?key=lockphp
 * '!^article/\d+$!i'=> array('类名', '方法'), http://www.domian.com/article/1
 */
$urls = array(
	//demo
	
	'!^$!' => array('Index','Page_Index'), //入口
	'!^index.php!' => array('Index','Page_Index'),//入口
	
	//用户
	'!^user/login(/|)(\?.*|)$!i' => array('User', 'Page_Login'),//登录
	
	//用户处理	
	'!^ajax/login(/|)$!i' => array('UserAjax', 'login'),//登录

);
