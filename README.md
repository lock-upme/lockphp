#Lock Php框架

这是一个轻盈简便PHP框架，适合中小企业WEB平台、WAP、API接口开发。

没有封装太多东西，自己想写什么就写什么，可扩展性强~

框架环境：

只要是php 5.o以上，带个地址重写就可以

#目录结构

	configs 					------- 配置文件
		db.inc.php			------- 数据库信息
		route.php			------- 路由表
		setting.inc.php	------- 配置信息
	controls					------- 类控制
		Index.class.php	------- 首页
	lib							------- 库
		db						------- 数据库
		magick				------- 图片裁剪
		mail						------- 邮件发送
		pagination			------- 分页
		smarty					------- 模板引擎
		snoopy				------- 远程页面抓取
		functions.php 	------- 常用方法, 可自行添加
		Lock.class.php	------- Lock 类
	media						------- 资源
		css						------- 全局样式
		images				------- 全局图片
		javascript			------- 全局JS文件，包括一些js库
		themes				------- 网站主题
			default			-------  默认主题
				css				------- 样式
				images		------- 图片
				javascript	------- JS
				index.tpl		------- 模板
	storage					------- 缓存，语言等文件
		cache					------- 文件缓存
		cn						------- 中文
			cache				------- smarty cache
			templates_c	------- smarty templates
		configs				------- 语言模板配置文件
			default			------- 默认主题配置
				en-us.lang	------- 英文
				zh-cn.lang  ------- 中文
		en
			cache
			templates_c
	temp						------- 临时
	.htaccess				------- 地址重写
	index.php				------- 入口
	
#数据调用
本框架没有采用数据模型，直接裸SQL语句运行,封装了一个mysqli类

		//取表数据
		$sql = "SELECT uid,username FROM ".TNAME."member limit 1";
		$this->db->query($sql);
		$result = $this->db->fetchRow();
		print_r($result);
		
		//单条缓存
		$sql = "SELECT uid,username FROM ".TNAME."member limit 1";
		$result = $this->db->fetchRowCache($sql);
		print_r($result);
		
		//多数组缓存
		$sql = "SELECT uid,username FROM ".TNAME."member limit 10";
		$result = $this->db->fetchRowsCache($sql);
		print_r($result);	
		
#Html模板调用
模板引擎采用smarty模板，直接用smarty方法即可

$this->smarty->display('index.tpl');

#路由
采用自定义路由表, 路由采用正则表达式编写；

URL只怕你想不到，正则完全能搞定

见 configs/route.php

		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
				
