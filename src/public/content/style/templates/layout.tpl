<!DOCTYPE html>
<html lang="it">
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

	<link rel="stylesheet" type="text/css" href="content/style/style.css?v=20120211" />
	<link rel="stylesheet" type="text/css" href="content/style/style.print.css?v=20120211" media="print" />
	{block name=head}{/block}
</head>
<body>
	{block name=header}	
		<div id="header">
			<div id="mainlogo"><a href="{$base_url}"><img src="content/style/images/logo.png" alt="klense" /></a></div>
			<!-- <div class="top_info">{if $authenticated} - <a href="?m=logout">Logout</a>{/if}</div> -->
			<div id="mainmenu">
				<div class="horizontalcssmenu">
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
					<!--<br style="clear: left;" />-->
				</div>
			</div>
			<div id="userinfo">
				<span>Beta version</span>
				{if $authenticated}
					<span><a href="profile">{$user.username}</a></span>
					<span><a href="logout">Esci</a></span>
				{else}
					<span><a href="login">Accedi</a> o <a href="register">registrati</a></span>
				{/if}
			</div>
		</div>
	{/block}
	<div id="wrapper">
		<div id="right_sidebar">
			{block name=top_sidebar}
			<script type="text/javascript">
			/* <![CDATA[ */
			document.write('<s'+'cript type="text/javascript" src="http://ad.altervista.org/js.ad/size=300X250/r='+new Date().getTime()+'"><\/s'+'cript>');
			/* ]]> */
			</script>
			{/block}
			{block name=bottom_sidebar}{/block}
		</div>
		<div id="main_content">
			{block name=content}{/block}
			{block name=footer}
				<div id="footer">
					<hr />
					<span>klense</span>
				</div>
			{/block}
		</div>
		</div>
</body>
</html>