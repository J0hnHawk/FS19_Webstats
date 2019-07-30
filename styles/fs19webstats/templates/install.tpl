{config_load file='../style.cfg'}
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<meta name="description" content="Farming Simulator 19 WebStats">
<meta name="author" content="John Hawk">
<link rel="icon" href="./images/favicon.ico">
<title>FS19 Web Stats</title>
<link href="{#CSS#}/bootstrap.min.css" rel="stylesheet">
<link href="{#CSS#}/theme.min.css" rel="stylesheet">
<link href="{#CSS#}/customstyle.css" rel="stylesheet">
<script src="{#SCRIPTS#}/jquery.min.js"></script>
<script src="{#SCRIPTS#}/bootstrap.min.js"></script>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark">
		<a class="navbar-brand" href="index.php">&nbsp;&nbsp;FS19 Web Stats&nbsp;&nbsp;</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="##TOGGLE##">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<form class="form-inline ml-auto" action="index.php?mode={$mode}" method="post">
				<span class="navbar-text">##CHOOSE_LANGUAGE_LABEL##:</span> <select class="form-control mx-sm-2" name="language"> {foreach $languages as $language}
					<option value="{$language.path}" {if $language.path==$smarty.session.language}selected{/if}>{$language.localName}</option> {/foreach}
				</select>
				<button type="submit" name="submit" value="language" class="btn btn-success">##CHANGE_BUTTON##</button>
				<span class="navbar-text ml-sm-2">##CHOOSE_STYLE##:</span> <select class="form-control mx-sm-2" name="style"> {foreach $styles as $styleData}
					<option value="{$styleData.path}" {if $styleData.path==$style}selected{/if}>{$styleData.name}</option> {/foreach}
				</select>
				<button type="submit" name="submit" value="style" class="btn btn-success">##CHANGE_BUTTON##</button>
			</form>
		</div>
	</nav>
	<main role="main"> {if $success}
	<div class="jumbotron">
		<div class="container">
			<h1>Farming Simulator 19 Web Stats</h1>
			<p>##CONFIG_SAVED##</p>
			<p>
				<a class="btn btn-primary btn-lg" href="index.php" role="button">##CONTINUE## &raquo;</a>
			</p>
		</div>
	</div>
	{else if $mode=='start'}
	<div class="jumbotron">
		<div class="container">
			<h1>Farming Simulator 19 Web Stats</h1>
			<hr class="my-4">
			<p class="lead">##DESCRIPTON##</p>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<!-- <div class="col-md-4">
				<h2>##HEAD_API##</h2>
				<p>##DESCR_API##</p>
				<p>
					<a class="btn btn-secondary" href="index.php?mode=api" role="button">##START_INSTALL## &raquo;</a>
				</p>
			</div> -->
			<div class="col-md-4">
				<h2>##HEAD_WEB##</h2>
				<p>##DESCR_WEB##</p>
				<p>
					<a class="btn btn-secondary" href="index.php?mode=web" role="button">##START_INSTALL## &raquo;</a>
				</p>
			</div>
			<div class="col-md-4">
				<h2>##HEAD_FTP##</h2>
				<p>##DESCR_FTP##</p>
				<p>
					<a class="btn btn-secondary" href="index.php?mode=ftp" role="button">##START_INSTALL## &raquo;</a>
				</p>
			</div>
			<div class="col-md-4">
				<h2>##HEAD_LOCAL##</h2>
				<p>##DESCR_LOCAL##</p>
				<p>
					<a class="btn btn-secondary" href="index.php?mode=local" role="button">##START_INSTALL## &raquo;</a>
				</p>
			</div>
		</div>
	</div>
	{else if $mode=='api'} {include file="install_api.tpl"} {else if $mode=='web'} {include file="install_web.tpl"} {else if $mode=='ftp'} {include file="install_ftp.tpl"} {else if $mode=='local'} {include file="install_local.tpl"} {/if} <br>
	<script>
	$(function () {
		  $('[data-toggle="popover"]').popover()
	})
	</script></main>
</body>
</html>
