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
	//example
	'!^$!' => array('Index', 'Page_Index'), //入口
	'!^index.php!' => array('Index', 'Page_Index'), //入口
	

	'!^article/\d+$!i'=> array('Article', 'showDetail'), //文章详情
	'!^article/add!' => array('Article','add'), //文章发布
	
);
