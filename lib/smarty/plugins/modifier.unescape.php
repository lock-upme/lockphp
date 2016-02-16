<?php
/**
 * Smarty plugin
 *
 * @package Smarty
 * @subpackage plugins
 */

/**
 * Smarty unescape modifier plugin
 *
 * Type:     modifier<br>
 * Name:     unescape<br>
 * Purpose:  unescape html entities
 *
 * @author Rodney Rehm
 * @param array $params parameters
 * @return string with compiled code
 */
function smarty_modifier_unescape($string, $esc_type = 'html')
{
    switch ($esc_type) {
        case 'entity':
        case 'htmlall':         

        case 'html':
            return htmlspecialchars_decode($string, ENT_QUOTES);

        case 'url':
            return rawurldecode($string);

        default:
            return $string;
    }
}

?>