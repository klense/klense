<?php /* Smarty version Smarty-3.1.7, created on 2012-02-03 15:31:39
         compiled from "content/style/templates/register.tpl" */ ?>
<?php /*%%SmartyHeaderCode:7959999464f2bd5d0b1c556-36885252%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6fbe382bc52f3a4f692097560cc1ab0786ce4c48' => 
    array (
      0 => 'content/style/templates/register.tpl',
      1 => 1328268567,
      2 => 'file',
    ),
    '5f55569baee4170e05e885f635a4e6dcbd728a25' => 
    array (
      0 => 'content/style/templates/layout.tpl',
      1 => 1328277992,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '7959999464f2bd5d0b1c556-36885252',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.7',
  'unifunc' => 'content_4f2bd5d0bf11c',
  'variables' => 
  array (
    'absolute_base_url' => 0,
    'pageTitle' => 0,
    'base_url' => 0,
    'authenticated' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_4f2bd5d0bf11c')) {function content_4f2bd5d0bf11c($_smarty_tpl) {?><!DOCTYPE html>
<html lang="it">
<head>
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
/*
		$(function() {
			if(!Modernizr.input.placeholder) {
				$('input, textarea').placeholder();
			}
		});*/
	</script>

	<link rel="stylesheet" type="text/css" href="content/js/csshorizontalmenu/csshorizontalmenu.css" />
	<script type="text/javascript" src="content/js/csshorizontalmenu/csshorizontalmenu.js">
	/***********************************************
	* CSS Horizontal List Menu- by JavaScript Kit (www.javascriptkit.com)
	* Menu interface credits: http://www.dynamicdrive.com/style/csslibrary/item/glossy-vertical-menu/ 
	* This notice must stay intact for usage
	* Visit JavaScript Kit at http://www.javascriptkit.com/ for this script and 100s more
	***********************************************/
	</script>

	<link rel="stylesheet" type="text/css" href="content/style/style.css?v=1" />
	<link rel="stylesheet" type="text/css" href="content/style/style.print.css?v=1" media="print" />
	
	<script>
		function formValid() {
			document.form.password_c.setCustomValidity(document.form.password_c.value != document.form.password.value ? 'Passwords do not match.' : '');
		}

		var RecaptchaOptions = {
			theme : 'white'
		};

		$(function() {
			$( "#birth_date" ).datepicker({
				dateFormat: "yy-mm-dd",
				changeMonth: true,
				changeYear: true,
				maxDate: "-10y",
				minDate: "-110y",
				yearRange: "-110:-10"
			});
		});
	</script>

</head>
<body>
		
		<div id="header">
			<div id="mainlogo"><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
"><img src="content/style/images/logo.png" /></a></div>
			<!-- <div class="top_info"><?php if ($_smarty_tpl->tpl_vars['authenticated']->value){?> - <a href="?m=logout">Logout</a><?php }?></div> -->
			<div id="mainmenu">
				<div class="horizontalcssmenu">
					<ul id="cssmenu1">
						<li><a href="<?php echo $_smarty_tpl->tpl_vars['base_url']->value;?>
">Home</a></li>
						<?php if ($_smarty_tpl->tpl_vars['authenticated']->value){?>
							<li><a href="http://www.javascriptkit.com/cutpastejava.shtml">Daniele</a></li>
							<li><a href="http://www.javascriptkit.com/">Carica</a></li>
						<?php }else{ ?>
							<li><a href="login">Accedi</a></li>
							<li><a href="register">Registrati</a></li>
						<?php }?>
						<li><a href="http://www.javascriptkit.com/">Esplora</a></li>
						<li><a href="http://www.javascriptkit.com/">Aiuto</a>
							<ul>
								<li><a href="http://www.javascriptkit.com/jsref/">Informazioni su klense</a></li>
								<li><a href="http://www.javascriptkit.com/domref/">Guida</a></li>
							</ul>
						</li>
					</ul>
					<!--<br style="clear: left;" />-->
				</div>
			</div>
		</div>
	
	<div id="wrapper">
		<div id="right_sidebar">
		</div>
		<div id="main_content">
			
	<div class="title">Registrazione</div>
	<div>
		Grazie per aver scelto di registrarti a klense!<br />
		Dopo aver immesso le informazioni richieste, sarai pronto per iniziare.
	</div>
	<form method="POST" action="<?php echo $_smarty_tpl->tpl_vars['base_url_params']->value;?>
" name="form" oninput="formValid();">
		<div class="registration_form">
			<?php if ($_smarty_tpl->tpl_vars['error']->value!=''){?>
			<div class="form_error"><?php echo $_smarty_tpl->tpl_vars['error']->value;?>
</div>
			<?php }?>
			<table>
				<tr>
					<td><label for="username">Nome utente</label></td>
					<td><input type="text" name="username" id="username" value="" pattern="^[a-zA-Z\d_]{5,20}$" required /> <span class="field_hint" title="da 5 a 20 caratteri, sono ammessi lettere, numeri e underscore">?</span></td>
				</tr>
				<tr>
					<td><label for="email">Indirizzo email</label></td>
					<td><input type="email" name="email" id="email" value="" required /></td>
				</tr>
				<tr>
					<td><label for="password">Password</label></td>
					<td><input type="password" name="password" id="password" value="" required pattern="^.{6,}$" /> <span class="field_hint" title="minimo 6 caratteri">?</span> </td>
				</tr>
				<tr>
					<td><label for="password_c">Ripeti password</label></td>
					<td><input type="password" name="password_c" id="password_c" value="" required /></td>
				</tr>
				<tr>
					<td><label for="birth_date">Data di nascita</label></td>
					<td><input type="text" name="birth_date" id="birth_date" value="" required /></td>
				</tr>
				<tr>
					<td><label for="timezone">Fuso orario</label></td>
					<td>
						<select size="1" id="timezone" name="timezone">
							<?php  $_smarty_tpl->tpl_vars['curr_tz'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['curr_tz']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['timezones']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['curr_tz']->key => $_smarty_tpl->tpl_vars['curr_tz']->value){
$_smarty_tpl->tpl_vars['curr_tz']->_loop = true;
?>
							<option value="<?php echo $_smarty_tpl->tpl_vars['curr_tz']->value;?>
" <?php if ($_smarty_tpl->tpl_vars['curr_tz']->value=="UTC"){?>selected<?php }?> ><?php echo $_smarty_tpl->tpl_vars['curr_tz']->value;?>
</option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td><?php echo $_smarty_tpl->tpl_vars['recaptcha']->value;?>
</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align:left"><label><input type="checkbox" name="accept" value="1" required /> Ho letto e accetto i <a href="#" target="_blank">termini di servizio</a> di klense.</label></td>
				</tr>
				<tr>
					<td colspan="2"><button class="orange" type="submit">Registrati</button></td>
				</tr>
			</table>
		</div>
	</form>

			
				<div id="footer">
					<hr />
					<span>klense</span>
				</div>
			
		</div>
		</div>
</body>
</html><?php }} ?>