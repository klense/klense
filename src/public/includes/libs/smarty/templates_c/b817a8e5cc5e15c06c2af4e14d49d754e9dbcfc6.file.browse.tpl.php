<?php /* Smarty version Smarty-3.1.7, created on 2012-02-08 11:31:27
         compiled from "content/style/templates/browse.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1036893084f30438bb939b3-65906062%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b817a8e5cc5e15c06c2af4e14d49d754e9dbcfc6' => 
    array (
      0 => 'content/style/templates/browse.tpl',
      1 => 1328696674,
      2 => 'file',
    ),
    '5f55569baee4170e05e885f635a4e6dcbd728a25' => 
    array (
      0 => 'content/style/templates/layout.tpl',
      1 => 1328697019,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '1036893084f30438bb939b3-65906062',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f30438bc11bc',
  'variables' => 
  array (
    'absolute_base_url' => 0,
    'pageTitle' => 0,
    'base_url' => 0,
    'authenticated' => 0,
    'user' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f30438bc11bc')) {function content_4f30438bc11bc($_smarty_tpl) {?><!DOCTYPE html>
<html lang="it">
<head>
	<!-- this is... nerd passion -->
	<meta charset="utf-8" />
	<meta name="Robots" content="All" />
	<meta name="Description" content="" />
	<meta name="Keywords" content="" />
	<base href="<?php echo $_smarty_tpl->tpl_vars['absolute_base_url']->value;?>
" />

	<link rel="shortcut icon" href="content/style/images/favicon.ico" />

	<title><?php echo $_smarty_tpl->tpl_vars['pageTitle']->value;?>
</title>

	<script type="text/javascript" src="content/js/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/ui-lightness/jquery-ui.css" type="text/css" media="all" />

	<script type="text/javascript" src="content/js/modernizr/modernizr.js"></script>
	<!--<script type="text/javascript" src="content/js/placeholder/jquery.placeholder.min.js"></script>-->

	<script>
		Modernizr.load({
			test: Modernizr.input.placeholder,
			nope: 'content/js/placeholder/jquery.placeholder.min.js',
			complete: function () {
				if(!Modernizr.input.placeholder) $('input, textarea').placeholder();
			}
		});
	</script>

	<link rel="stylesheet" type="text/css" href="content/js/csshorizontalmenu/csshorizontalmenu.css" />
	<script type="text/javascript" src="content/js/csshorizontalmenu/csshorizontalmenu.js">
	// CSS Horizontal List Menu- by JavaScript Kit (www.javascriptkit.com)
	// Menu interface credits: http://www.dynamicdrive.com/style/csslibrary/item/glossy-vertical-menu/ 
	// This notice must stay intact for usage
	// Visit JavaScript Kit at http://www.javascriptkit.com/ for this script and 100s more
	</script>

	<link rel="stylesheet" type="text/css" href="content/style/style.css?v=1" />
	<link rel="stylesheet" type="text/css" href="content/style/style.print.css?v=1" media="print" />
	
</head>
<body>
		
		<div id="header">
			<div id="mainlogo"><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
"><img src="content/style/images/logo.png" alt="klense" /></a></div>
			<!-- <div class="top_info"><?php if ($_smarty_tpl->tpl_vars['authenticated']->value){?> - <a href="?m=logout">Logout</a><?php }?></div> -->
			<div id="mainmenu">
				<div class="horizontalcssmenu">
					<ul id="cssmenu1">
						<li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
">Home</a></li>
						<?php if ($_smarty_tpl->tpl_vars['authenticated']->value){?>
							<li><a href="profile"><?php echo $_smarty_tpl->tpl_vars['user']->value['username'];?>
</a></li>
							<li><a href="image/upload">Carica</a></li>
						<?php }else{ ?>
							<li><a href="login">Accedi</a></li>
							<li><a href="register">Registrati</a></li>
						<?php }?>
						<li><a href="browse">Esplora</a>
							<ul>
								<li><a href="browse/toptags">Tag più usati</a></li>
							</ul>
						</li>
						<li><a href="help">Aiuto</a>
							<ul>
								<li><a href="help/info">Informazioni su klense</a></li>
							</ul>
						</li>
					</ul>
					<!--<br style="clear: left;" />-->
				</div>
			</div>
			<div id="userinfo">
				<span>Beta version</span>
				<?php if ($_smarty_tpl->tpl_vars['authenticated']->value){?>
					<span><a href="profile"><?php echo $_smarty_tpl->tpl_vars['user']->value['username'];?>
</a></span>
					<span><a href="logout">Esci</a></span>
				<?php }else{ ?>
					<span><a href="login">Accedi</a> o <a href="register">registrati</a></span>
				<?php }?>
			</div>
		</div>
	
	<div id="wrapper">
		<div id="right_sidebar">
			
			<script type="text/javascript">
			/* <![CDATA[ */
			document.write('<s'+'cript type="text/javascript" src="http://ad.altervista.org/js.ad/size=300X250/r='+new Date().getTime()+'"><\/s'+'cript>');
			/* ]]> */
			</script>
			
			
		</div>
		<div id="main_content">
			
	<div class="title">Le ultime immagini caricate</div>
	<table class="imagelist">
		<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['name'] = 'outer_imgarr';
$_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['images']->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['start'] = (int)0;
$_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['step'] = ((int)3) == 0 ? 1 : (int)3;
$_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['show'] = true;
$_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['loop'];
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['start'] < 0)
    $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['start'] = max($_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['step'] > 0 ? 0 : -1, $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['loop'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['start']);
else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['start'] = min($_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['loop'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['loop']-1);
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['total'] = min(ceil(($_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['loop'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['start'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['start']+1)/abs($_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['step'])), $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['max']);
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['outer_imgarr']['total']);
?>
			<tr>
			<?php if (isset($_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr'])) unset($_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']);
$_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['name'] = 'inner_imgarr';
$_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['loop'] = is_array($_loop=$_smarty_tpl->tpl_vars['images']->value) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['start'] = (int)$_smarty_tpl->getVariable('smarty')->value['section']['outer_imgarr']['index'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['step'] = ((int)1) == 0 ? 1 : (int)1;
$_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['max'] = (int)3;
$_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['show'] = true;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['max'] < 0)
    $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['max'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['loop'];
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['start'] < 0)
    $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['start'] = max($_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['step'] > 0 ? 0 : -1, $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['loop'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['start']);
else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['start'] = min($_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['loop'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['loop']-1);
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['show']) {
    $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['total'] = min(ceil(($_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['step'] > 0 ? $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['loop'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['start'] : $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['start']+1)/abs($_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['step'])), $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['max']);
    if ($_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['total'] == 0)
        $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['show'] = false;
} else
    $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['total'] = 0;
if ($_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['show']):

            for ($_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['index'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['start'], $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['iteration'] = 1;
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['iteration'] <= $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['total'];
                 $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['index'] += $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['step'], $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['iteration']++):
$_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['rownum'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['iteration'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['index_prev'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['index'] - $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['index_next'] = $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['index'] + $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['step'];
$_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['first']      = ($_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['iteration'] == 1);
$_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['last']       = ($_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['iteration'] == $_smarty_tpl->tpl_vars['smarty']->value['section']['inner_imgarr']['total']);
?>
				<td>
					<div>
						<a href="<?php echo $_smarty_tpl->tpl_vars['images']->value[$_smarty_tpl->getVariable('smarty')->value['section']['inner_imgarr']['index']]['imgurl'];?>
"><img src="<?php echo $_smarty_tpl->tpl_vars['images']->value[$_smarty_tpl->getVariable('smarty')->value['section']['inner_imgarr']['index']]['filename'];?>
" title="<?php echo $_smarty_tpl->tpl_vars['images']->value[$_smarty_tpl->getVariable('smarty')->value['section']['inner_imgarr']['index']]['displayName'];?>
" alt="<?php echo $_smarty_tpl->tpl_vars['images']->value[$_smarty_tpl->getVariable('smarty')->value['section']['inner_imgarr']['index']]['displayName'];?>
" /></a>
						<div>
							di <a href="<?php echo $_smarty_tpl->tpl_vars['images']->value[$_smarty_tpl->getVariable('smarty')->value['section']['inner_imgarr']['index']]['user_url'];?>
"><?php echo $_smarty_tpl->tpl_vars['images']->value[$_smarty_tpl->getVariable('smarty')->value['section']['inner_imgarr']['index']]['user_publicname'];?>
</a>
						</div>
					</div>
				</td>
			<?php endfor; endif; ?>
			</tr>
		<?php endfor; endif; ?>
	</table>

			
				<div id="footer">
					<hr />
					<span>klense</span>
				</div>
			
		</div>
		</div>
</body>
</html><?php }} ?>