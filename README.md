#LockPHP2.0 框架

这是一个轻盈简便PHP框架，适合中小企业WEB平台、WAP、API接口开发。

个人闲暇中写的，没有封装太多东西，自己想写什么就写什么，可扩展性强~。在实际项目中也实践过~

框架环境：

PHP版本7.0以上，低版本的有的新写法可能不支持，升级到高版本
fix:2016/09/09
1.采用PHP7.0
2.添加全局G方法
3.MySQLI for PHP7.0
4.升级smarty3模块
5.优化ＤＢ引用
6.增加AES/DES加密
7.增加雪花ID自增
8.增加图片验证码
9.优化模板结构

#目录结构

	configs             ------- 配置文件
		db.php			    ------- 数据库信息
        dict.php            ------- 字典信息
        dict                ------- 字典配置目录
        redis.php           ------- redis配置信息
		route.php			------- 路由表
		setting.php	        ------- 配置信息
		global.php	        ------- 全局配置信息
	controls					------- 类控制
		Index.class.php	------- 首页
	lib							------- 库
		db						------- 数据库
        encryption              ------- AES/DES加密
		magick                  ------- 图片裁剪
		mail                    ------- 邮件发送
		pagination              ------- 分页
        particle                ------- 雪花ＩＤ
		smarty3                 ------- 模板引擎
		qrcode                  ------- 二维码
		snoopy                  ------- 远程页面抓取
        ＶalidateCode           ------- 图片验证码
		functions.php           ------- 常用方法, 可自行添加
		Lock.class.php          ------- Lock 类
	media						------- 资源
		css						------- 全局样式
		images				------- 全局图片
		js                  ------- 全局JS文件，包括一些js库
		themes				------- 网站主题
			default			-------  默认主题
				css			------- 样式
				images		------- 图片
				js          ------- JS
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

#增加配置字典获取方法 2016/09/09
获取global.php配置文件具体值
deomo:
G('dict.sex')
G('ip')

		
#Html模板调用
模板引擎采用smarty模板，直接用smarty方法即可

$this->smarty->display('index.tpl');

#路由
采用自定义路由表, 路由采用正则表达式编写；

URL只怕你想不到，正则完全能搞定

见 configs/route.php

		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
				
