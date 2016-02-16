<?php
session_start();
error_reporting(0);
//ini_set('display_errors','on');
//error_reporting(E_ALL);

//时区
date_default_timezone_set('Asia/Shanghai');

//目录设定
define('D_ROOT', $_SERVER['DOCUMENT_ROOT'].'/');
define('D_CACHE', $_SERVER['DOCUMENT_ROOT'].'/storage/cache');
define('W_DOMAIN','http://'.$_SERVER['HTTP_HOST']);
define('SITE_MEDIA','http://'.$_SERVER['HTTP_HOST'].'/media');
define('SITE_PIC','http://'.$_SERVER['HTTP_HOST'].'/media/uploadfile');

define('KEY', '410ddc3949ba59abbe56e057f20r883e'); //密匙
define('CACHE_EXPIRE',10);//缓存时间

//图片裁剪
define('ExecuteImg' , 'GD'); //GraphicsMagick //ImageMagick.php //GD
//define('GM', 'C:\Progra~1\GraphicsMagick-1.3.21-Q16\gm.exe'); //win
define('GM', '/usr/local/bin/gm'); //linux
define('GM_DEBUG', 'y');

require_once(D_ROOT.'configs/route.php');//路由表

require_once(D_ROOT.'lib/functions.php');//常用方法
require_once(D_ROOT.'lib/smarty/Smarty.class.php');//引入smarty
require_once(D_ROOT.'lib/Lock.class.php');


