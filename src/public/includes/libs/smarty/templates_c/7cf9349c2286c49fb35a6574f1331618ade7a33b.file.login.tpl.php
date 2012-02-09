<?php /* Smarty version Smarty-3.1.7, created on 2012-02-08 10:06:10
         compiled from "content/style/templates/login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:17078761794f2bc84709fcc1-42005788%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '7cf9349c2286c49fb35a6574f1331618ade7a33b' => 
    array (
      0 => 'content/style/templates/login.tpl',
      1 => 1328274607,
      2 => 'file',
    ),
    '5f55569baee4170e05e885f635a4e6dcbd728a25' => 
    array (
      0 => 'content/style/templates/layout.tpl',
      1 => 1328695542,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '17078761794f2bc84709fcc1-42005788',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f2bc8471170f',
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
<?php if ($_valid && !is_callable('content_4f2bc8471170f')) {function content_4f2bc8471170f($_smarty_tpl) {?><!DOCTYPE html>
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
				<?php if ($_smarty_tpl->tpl_vars['authenticated']->value){?>
					<a href="profile"><?php echo $_smarty_tpl->tpl_vars['user']->value['username'];?>
</a>
					<a href="logout">Esci</a>
				<?php }else{ ?>
					<a href="login">Accedi</a> o <a href="register">registrati</a>
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
			
	<div class="title login_title">Accedi</div>
	<div class="login_form">
		<form action="<?php echo $_smarty_tpl->tpl_vars['base_url_params']->value;?>
" method="POST">
			<?php if ($_smarty_tpl->tpl_vars['error']->value!=''){?>
			<div class="form_error"><?php echo $_smarty_tpl->tpl_vars['error']->value;?>
</div>
			<?php }?>
			<input type="text" name="username" placeholder="Nome utente" />
			<input type="password" name="password" placeholder="Password" />
			<button type="submit" class="orange">Accedi</button>
		</form>
	</div>

			
				<div id="footer">
					<hr />
					<span>klense</span>
				</div>
			
		</div>
		</div>
</body>
</html><?php }} ?>