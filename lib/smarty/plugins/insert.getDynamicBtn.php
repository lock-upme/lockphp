<?php
/**
 * Smarty shared plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Function: smarty_make_timestamp<br>
 * Purpose:  used by other smarty functions to make a timestamp
 *           from a string.
 * @author   Monte Ohrt <monte at ohrt dot com>
 * @param string
 * @return string
 */
function smarty_insert_getDynamicBtn($params)
{
    
    $type =  $params['type'];
    if ($type == 'friend') {
    	$ischeck = $params['ischeck'];
    	if ($ischeck) {
    		return '<a href="javascript:;" class="btn-index btn-on">已加好友</a>';
    	} else {
    		return '<a href="javascript:;" class="btn-index btn-p addFriendRequest" data-fuid="{{$v.uid}}"  data-fusername="{{smartUserName uid=$v.uid}}">加TA好友</a>';
    	}
    	
    }

}

/* vim: set expandtab: */

?>
