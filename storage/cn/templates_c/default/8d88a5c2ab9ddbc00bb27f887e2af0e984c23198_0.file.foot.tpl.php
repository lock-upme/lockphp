<?php
/* Smarty version 3.1.29, created on 2016-09-09 17:41:27
  from "E:\lockphp-v2\media\themes\default\libpage\foot.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_57d283c70d2036_13376104',
  'file_dependency' => 
  array (
    '8d88a5c2ab9ddbc00bb27f887e2af0e984c23198' => 
    array (
      0 => 'E:\\lockphp-v2\\media\\themes\\default\\libpage\\foot.tpl',
      1 => 1473413253,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_57d283c70d2036_13376104 ($_smarty_tpl) {
echo '<script'; ?>
 src="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'site_media');?>
/js/jquery.min.js?<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'global_date');?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'site_template');?>
/js/common.js?<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'global_date');?>
"><?php echo '</script'; ?>
>
<?php echo '<script'; ?>
 src="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'site_template');?>
/js/default.js?<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'global_date');?>
"><?php echo '</script'; ?>
><?php }
}
