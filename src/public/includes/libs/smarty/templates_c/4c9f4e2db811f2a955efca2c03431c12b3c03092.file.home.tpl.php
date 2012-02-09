<?php /* Smarty version Smarty-3.1.7, created on 2012-02-08 12:14:55
         compiled from "content/style/templates/home.tpl" */ ?>
<?php /*%%SmartyHeaderCode:12135119184f2bd4e13cfc04-32478570%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '4c9f4e2db811f2a955efca2c03431c12b3c03092' => 
    array (
      0 => 'content/style/templates/home.tpl',
      1 => 1328176990,
      2 => 'file',
    ),
    '5f55569baee4170e05e885f635a4e6dcbd728a25' => 
    array (
      0 => 'content/style/templates/layout.tpl',
      1 => 1328697019,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '12135119184f2bd4e13cfc04-32478570',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f2bd4e143bc7',
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
<?php if ($_valid && !is_callable('content_4f2bd4e143bc7')) {function content_4f2bd4e143bc7($_smarty_tpl) {?><!DOCTYPE html>
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
			
	<div style="text-align:center;">
		<img src="http://cdn.blogosfere.it/cheapechic/images/blake-lively%20serena%20chanel%20bags.jpg" style="width:210px; height:210px;" />
		<img src="http://www.viaggiareliberi.it/Ospiti/Bepperuperto_pol_rangi_spiaggia.jpg" style="width:210px; height:210px;"  />
		<img src="http://www.lifeinitaly.com/files/MotoGriso3.jpg" style="width:210px; height:210px;"  />
	</div>

			
				<div id="footer">
					<hr />
					<span>klense</span>
				</div>
			
		</div>
		</div>
</body>
</html><?php }} ?>