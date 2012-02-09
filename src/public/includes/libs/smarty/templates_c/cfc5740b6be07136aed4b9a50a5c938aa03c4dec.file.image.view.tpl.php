<?php /* Smarty version Smarty-3.1.7, created on 2012-02-08 23:11:49
         compiled from "content/style/templates/image.view.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2246965824f3128da60c612-29029567%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cfc5740b6be07136aed4b9a50a5c938aa03c4dec' => 
    array (
      0 => 'content/style/templates/image.view.tpl',
      1 => 1328742707,
      2 => 'file',
    ),
    '5f55569baee4170e05e885f635a4e6dcbd728a25' => 
    array (
      0 => 'content/style/templates/layout.tpl',
      1 => 1328742570,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2246965824f3128da60c612-29029567',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f3128da6a263',
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
<?php if ($_valid && !is_callable('content_4f3128da6a263')) {function content_4f3128da6a263($_smarty_tpl) {?><!DOCTYPE html>
<html lang="it">
<head>
	<meta content="text/html; charset=UTF-8" http-equiv="content-type" />
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
	
	<script>
		$(function() {
			$(".contentSlide.closed").hide();
			$(".headingSlide").click(function() {
				if ($(this).next(".contentSlide").is(":visible")) {
					$(this).removeClass("opened closed").addClass("closed");
					$(this).next(".contentSlide").removeClass("opened closed").addClass("closed");
				} else {
					$(this).removeClass("opened closed").addClass("opened");
					$(this).next(".contentSlide").removeClass("opened closed").addClass("opened");
				}
				$(this).next(".contentSlide").slideToggle(200);
			});
		});
	</script>

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
			
			
	<div class="image_sidebar_sections">
		<div class="image_owner">Di <a href="<?php echo $_smarty_tpl->tpl_vars['user_url']->value;?>
"><?php echo $_smarty_tpl->tpl_vars['user_publicname']->value;?>
</a></div>
		<?php if (isset($_smarty_tpl->tpl_vars['myexif']->value['make_model'])){?>
			<div>
				Foto scattata con una <a href="#"><?php echo $_smarty_tpl->tpl_vars['myexif']->value['make_model'];?>
</a>
				<?php if (isset($_smarty_tpl->tpl_vars['myexif']->value['shot_date'])){?>in data <?php echo $_smarty_tpl->tpl_vars['myexif']->value['shot_date'];?>
, ore <?php echo $_smarty_tpl->tpl_vars['myexif']->value['shot_time'];?>
.<?php }else{ ?>.<?php }?>
			</div>
		<?php }else{ ?>
			<?php if (isset($_smarty_tpl->tpl_vars['myexif']->value['shot_date'])){?>
				<div>Foto scattata in data <?php echo $_smarty_tpl->tpl_vars['myexif']->value['shot_date'];?>
, ore <?php echo $_smarty_tpl->tpl_vars['myexif']->value['shot_time'];?>
.</div>
			<?php }?>
		<?php }?>
		<div>
			<div class="headingSlide closed"><a href="#" onclick="return false;">Dati EXIF</a></div>
			<div class="contentSlide closed" style="padding">
				<table>
					<tr>
						<td>Exif 1</td>
						<td>val1</td>
					</tr>
					<tr>
						<td>Exif 2</td>
						<td>val2</td>
					</tr>
				</table>
			</div>
		</div>
		<div>
			<div class="headingSlide opened"><a href="#" onclick="return false;">Altre dimensioni</a></div>
			<div class="contentSlide opened" style="padding">
				<ul>
					<?php  $_smarty_tpl->tpl_vars['size'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['size']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['otherSizes']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['size']->key => $_smarty_tpl->tpl_vars['size']->value){
$_smarty_tpl->tpl_vars['size']->_loop = true;
?>
						<li><a href="#"><?php echo $_smarty_tpl->tpl_vars['size']->value['descr'];?>
 (<?php echo $_smarty_tpl->tpl_vars['size']->value['w'];?>
x<?php echo $_smarty_tpl->tpl_vars['size']->value['h'];?>
)</a> · <a href="<?php echo $_smarty_tpl->tpl_vars['size']->value['link'];?>
">download</a></li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<div>
			<div class="headingSlide opened"><a href="#" onclick="return false;">Tag</a></div>
			<div class="contentSlide opened">
				<div class="tags"><?php  $_smarty_tpl->tpl_vars['tag'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['tag']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['tags']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['tag']->key => $_smarty_tpl->tpl_vars['tag']->value){
$_smarty_tpl->tpl_vars['tag']->_loop = true;
?><a href="#"><?php echo $_smarty_tpl->tpl_vars['tag']->value;?>
</a> <?php } ?></div>
			</div>
		</div>
	</div>

		</div>
		<div id="main_content">
			
	<div class="image_container">
		<img src="<?php echo $_smarty_tpl->tpl_vars['image_filename']->value;?>
" alt="<?php echo $_smarty_tpl->tpl_vars['image_displayName']->value;?>
" /></a>
	</div>
	<div class="image_title"><?php echo $_smarty_tpl->tpl_vars['image_displayName']->value;?>
</div>
	<div class="image_description">Free description.</div>

			
				<div id="footer">
					<hr />
					<span>klense</span>
				</div>
			
		</div>
		</div>
</body>
</html><?php }} ?>