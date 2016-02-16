<?php
/**
 * @desc enter guide
 */
header('Content-Type: text/html; charset=utf-8');
require_once('configs/setting.inc.php');

function __autoload($class){
	require_once("./controls/" . $class . ".class.php");
}

route($urls);