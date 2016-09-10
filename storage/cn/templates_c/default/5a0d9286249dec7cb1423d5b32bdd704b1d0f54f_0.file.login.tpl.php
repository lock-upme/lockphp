<?php
/* Smarty version 3.1.29, created on 2016-09-09 18:02:44
  from "E:\lockphp-v2\media\themes\default\users\login.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_57d288c4d05b58_36262593',
  'file_dependency' => 
  array (
    '5a0d9286249dec7cb1423d5b32bdd704b1d0f54f' => 
    array (
      0 => 'E:\\lockphp-v2\\media\\themes\\default\\users\\login.tpl',
      1 => 1473415362,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
    'file:libpage/meta.tpl' => 1,
    'file:libpage/head.tpl' => 1,
    'file:libpage/foot.tpl' => 1,
  ),
),false)) {
function content_57d288c4d05b58_36262593 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:libpage/meta.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

<title>登录-<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'global_title');?>
</title>
<meta name="keywords" content="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'global_keywords');?>
" />
<meta name="description" content="<?php echo $_smarty_tpl->smarty->ext->configLoad->_getConfigVariable($_smarty_tpl, 'global_desp');?>
" />
<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:libpage/head.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

</head>
<body style="font-size:26px; text-align:center;margin:130px;">
登录页
<br/><br/>
适合中小企业平台构架及API设计，简单快速搭建。
<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, "file:libpage/foot.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 0, $_smarty_tpl->cache_lifetime, array(), 0, false);
?>

</body>
</html>
<?php }
}
