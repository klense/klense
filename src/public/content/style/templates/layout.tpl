<!DOCTYPE html>
<html lang="en, it">
<head>
	<meta content="text/html; charset=UTF-8" http-equiv="content-type" />
	<!-- this is... nerd passion -->
	<meta charset="utf-8" />
	<meta name="Robots" content="All" />
	<meta name="Description" content="" />
	<meta name="Keywords" content="" />
	<base href="{$absolute_base_url}" />

	<link rel="shortcut icon" href="{$absolute_base_url}/content/style/images/favicon.ico" />

	<title>{block name=title}{$pageTitle}{/block}</title>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/themes/ui-lightness/jquery-ui.css" type="text/css" media="all" />
	
	<link rel="stylesheet" type="text/css" href="content/js/csshorizontalmenu/csshorizontalmenu.css" />

	<!--<script src="http://cdn.jquerytools.org/1.2.6/all/jquery.tools.min.js"></script>
	<link rel="stylesheet" type="text/css" href="content/js/jquery.tools/tooltip/tooltip.css" />-->

	<script type="text/javascript" src="content/js/modernizr/modernizr.js"></script>
	<script type="text/javascript" src="content/js/pages/common.js"></script>

	<link rel="stylesheet" type="text/css" href="content/style/style.css?v=20120213" />
	<link rel="stylesheet" type="text/css" href="content/style/style.print.css?v=20120213" media="print" />
	{block name=head}{/block}
	{$analytics_code}
</head>
<body>
	{block name=header}	
		<header id="header">
			<div id="mainlogo"><a href="{$base_url}"><img src="content/style/images/logo.png" alt="klense" /></a></div>
			<!-- <div class="top_info">{if $authenticated} - <a href="?m=logout">Logout</a>{/if}</div> -->
			<nav class="horizontalcssmenu">
				<ul id="cssmenu1">
					<li><a href="{$base_url}">Home</a></li>
					{if $authenticated}
						<li><a href="profile">{$user.username}</a></li>
						<li><a href="image/upload">Carica</a></li>
					{else}
						<li><a href="login">Accedi</a></li>
						<li><a href="register">Registrati</a></li>
					{/if}
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
				<script type="text/javascript" src="content/js/csshorizontalmenu/csshorizontalmenu.js">
				// CSS Horizontal List Menu- by JavaScript Kit (www.javascriptkit.com)
				// Menu interface credits: http://www.dynamicdrive.com/style/csslibrary/item/glossy-vertical-menu/ 
				// This notice must stay intact for usage
				// Visit JavaScript Kit at http://www.javascriptkit.com/ for this script and 100s more
				</script>
			</nav>
			<div id="userinfo">
				<span>Beta version</span>
				{if $authenticated}
					<span><a href="profile">{$user.username}</a></span>
					<span><a href="logout">Esci</a></span>
				{else}
					<span><a href="login">Accedi</a> o <a href="register">registrati</a></span>
				{/if}
			</div>
		</header>
	{/block}
	<div id="wrapper">
		{if $right_sidebar}
			<div id="right_sidebar">
				{block name=top_sidebar}{$sidebar_top_ad}{/block}
				{block name=middle_sidebar}{/block}
				{block name=bottom_sidebar}{/block}
			</div>
		{/if}
		<div id="main_content" class="{if $right_sidebar}with-sidebar{else}without-sidebar{/if}">
			{block name=content}{/block}
			{block name=footer}
				<footer id="footer">
					{$before_footer_ad}
					<hr />
					<span>klense</span>
				</footer>
			{/block}
		</div>
		</div>
</body>
</html>