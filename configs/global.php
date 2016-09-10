<?php

$_GLOBAL = array(
		'time' => $_SERVER['REQUEST_TIME'],
		'ip' => getIp(),
		'cookie' => array('name' => '_nahehuowap', 'time' =>time()+3600*72, 'path' => '/',  'domain' => '.'. $_SERVER['SERVER_NAME']),
		'mime' => array(
				'img' => array('jpeg' => 'image/jpeg', 'jpg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif', 'bmp' => 'image/x-ms-bmp'),
				'file' => array('mp3' => 'audio/mpeg', 'xls'=>'application/vnd.ms-excel', 'xlsx'=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'doc'=>'application/msword', 'docx'=>'application/vnd.openxmlformats-officedocument.wordprocessingml.document','txt'=>'text/plain','pdf'=>'application/pdf','jpg' => 'image/jpeg'),
				'pkg' => array('zip' => '504b0304', 'rar' => '526172211a0700', '7z' => '377abcaf271c'),
				'src' => array('cdr' => '52494646', 'psd' => '38425053000100000000000000', 'ai' => '252150532d', 'ai_' => '255044462d312e', 'eps' => 'c5d0d3c6', 'fla' => 'd0cf11e0a1b11ae100'),    //'pdf' => '255044462d312e',
		),
		'filedir' => 'files/',
		'thumbdir' => 'thumb/',
		'geometry' => array(			
				'avatar' => array('big' => '210x210', 'small' => '120x120'),
				'common' => array('big' => '100x100', 'small' => '30x30'),
		),
		'dict' => require 'dict.php',
		'redis' => require 'redis.php'
);


