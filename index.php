<?php
/**
 * @desc enter guide
 */
header('Content-Type: text/html; charset=utf-8');
require_once('configs/setting.php');

function autoload($class) {
	//修订smarty
	if (substr($class, 0, 15) == "Smarty_Internal" || substr($class, 0, 15) == "Smarty_Template") {
		
	} else {		
		require_once("./controls/" . $class . ".class.php");
	}
}
spl_autoload_register("autoload");

route($urls);