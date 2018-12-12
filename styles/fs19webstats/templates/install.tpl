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
			<div class="col-md-4">
				<h2>##HEAD_API##</h2>
				<p>##DESCR_API##</p>
				<p>
					<a class="btn btn-secondary" href="index.php?mode=api" role="button">##START_INSTALL## &raquo;</a>
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
	{else if $mode=='api'}
	<div class="container">
		<h2>##INSTALL_TITLE## ##HEAD_API##</h2>
		<form class="form-horizontal" action="index.php?mode={$mode}" method="post">
			{if $fsockopen} {if $error}{$error}{/if}
			<div class="form-group">
				<label for="Server-IP" class="col-sm-3 control-label">##DS_LABEL1##</label>
				<div class="col-sm-7">
					<input type="ip" name="serverip" class="form-control" id="Server-IP" placeholder="##DS_PLACEHOLDE1##" {if isset($postdata.serverIp)}value="{$postdata.serverIp}"{/if}> <span id="helpBlock" class="help-block">##DS_HELP_BLOCK1##</span>
				</div>
			</div>
			<div class="form-group">
				<label for="Server-Port" class="col-sm-3 control-label">##DS_LABEL2##</label>
				<div class="col-sm-7">
					<input type="text" name="serverport" class="form-control" id="Server-Port" placeholder="##DS_PLACEHOLDE2##" {if isset($postdata.serverPort)}value="{$postdata.serverPort}"{/if}> <span id="helpBlock" class="help-block">##DS_HELP_BLOCK2##</span>
				</div>
			</div>
			<div class="form-group">
				<label for="Server-Code" class="col-sm-3 control-label">##DS_LABEL3##</label>
				<div class="col-sm-7">
					<input type="text" name="servercode" class="form-control" id="Server-Code" placeholder="##DS_PLACEHOLDE3##" {if isset($postdata.serverCode)}value="{$postdata.serverCode}"{/if}> <span id="helpBlock" class="help-block">##DS_HELP_BLOCK3##</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">##DS_LABEL4##</label>
				<div class="col-sm-7">
					<select class="form-control" id="map" name="map"> {foreach $maps as $mapDir => $mapData}
						<option value="{$mapDir}">{$mapData.Name} {$mapData.Version}</option> {/foreach}
					</select><span id="helpBlock" class="help-block">##DS_HELP_BLOCK4##</span>
				</div>
			</div>
			<div class="form-group">
				<label for="password" class="col-sm-3 control-label">##DS_LABEL5##</label>
				<div class="col-sm-7 form-inline">
					<input type="password" name="adminpass1" class="form-control" id="password" placeholder="##DS_PLACEHOLDE5##">&nbsp;<input type="password" name="adminpass2" class="form-control" id="password" placeholder="##DS_PLACEHOLDE6##"> <span id="helpBlock" class="help-block">##DS_HELP_BLOCK5##</span>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-7 col-sm-3">
					<button type="submit" name="submit" value="gameSettings" class="pull-right btn btn-primary btn-block">##SAVE_CONFIG##</button>
				</div>
			</div>
			{else}
			<p class="lead">##NO_INSTALL_1##</p>
			<p class="lead">##NO_INSTALL_2##</p>
			{/if}
		</form>
	</div>
	{else if $mode=='ftp'}
	<div class="container">
		<h2>##INSTALL_TITLE## ##HEAD_FTP##</h2>
		<form class="form-horizontal" action="index.php?mode={$mode}" method="post">
			{if $error}{$error}{/if}
			<div class="form-group row">
				<label for="ftpserver" class="col-sm-3 col-form-label">##FTPADRESS##</label>
				<div class="col-sm-7">
					<input type="ip" class="form-control" id="ftpserver" name="ftpserver" placeholder="0.0.0.0">
				</div>
			</div>
			<div class="form-group row">
				<label for="ftpport" class="col-sm-3 col-form-label">##FTPPORT##</label>
				<div class="col-sm-7">
					<input type="text" class="form-control" id="ftpport" name="ftpport" placeholder="21">
				</div>
			</div>
			<div class="form-group row">
				<label for="ftppath" class="col-sm-3 col-form-label">##FTPPATH##</label>
				<div class="col-sm-7">
					<input type="text" class="form-control" id="ftppath" name="ftppath" placeholder="/folder/subfolder/" value="/profile/savegame1/">
				</div>
			</div>
			<div class="form-group row">
				<label for="ftpuser" class="col-sm-3 col-form-label">##FTPUSER##</label>
				<div class="col-sm-7">
					<input type="text" class="form-control" id="ftpuser" name="ftpuser">
				</div>
			</div>
			<div class="form-group row">
				<label for="ftppass" class="col-sm-3 col-form-label">##FTPPASSWORD##</label>
				<div class="col-sm-7">
					<input type="text" class="form-control" id="ftppass" name="ftppass">
				</div>
			</div>
			<div class="form-group row">
				<label class="col-sm-3 control-label">##DS_LABEL4##</label>
				<div class="col-sm-7">
					<select class="form-control" id="map" name="map"> {foreach $maps as $mapDir => $mapData}
						<option value="{$mapDir}">{$mapData.Name} {$mapData.Version}</option> {/foreach}
					</select><span id="helpBlock" class="help-block">##DS_HELP_BLOCK4##</span>
				</div>
			</div>
			<div class="form-group row">
				<label for="password" class="col-sm-3 control-label">##DS_LABEL5##</label>
				<div class="col-sm-7 form-inline">
					<input type="password" name="adminpass1" class="form-control" id="password" placeholder="##DS_PLACEHOLDE5##">&nbsp;<input type="password" name="adminpass2" class="form-control" id="password" placeholder="##DS_PLACEHOLDE6##"> <span id="helpBlock" class="help-block">##DS_HELP_BLOCK5##</span>
				</div>
			</div>
			<div class="form-group row">
				<div class="col-sm-offset-7 col-sm-3">
					<button type="submit" name="submit" value="gameSettings" class="pull-right btn btn-primary btn-block">##SAVE_CONFIG##</button>
				</div>
			</div>
		</form>
	</div>
	{else if $mode=='local'}
	<div class="container">
		<h2>##INSTALL_TITLE## ##HEAD_LOCAL##</h2>
		<form class="form-horizontal" action="index.php?mode={$mode}" method="post">
			{if $error}{$error}{/if}
			<p>
				##INTRO1## <a href="https://www.farming-simulator.com/mod.php?lang=de&country=de&mod_id=50533&title=fs2017">##LINK_TEXT##</a>##INTRO2##
			</p>
			<input type="file" name="file" style="visibility: hidden;" id="path2savegame" />
			<div class="form-group">
				<label for="savepath" class="col-sm-3 control-label">##LS_LABEL1##</label>
				<div class="col-sm-7">
					<input type="text" name="savepath" class="form-control" id="savepath" placeholder="##LS_PLACEHOLDE1##" {if isset($postdata.path)> 0}value="{$postdata.path}"{/if}> <span id="helpBlock" class="help-block">##LS_HELP_BLOCK1##</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3 control-label">##LS_LABEL2##</label>
				<div class="col-sm-7">
					<select class="form-control" id="map" name="map"> {foreach $maps as $mapDir => $mapData}
						<option value="{$mapDir}">{$mapData.Name} {$mapData.Version}</option> {/foreach}
					</select><span id="helpBlock" class="help-block">##LS_HELP_BLOCK2##</span>
				</div>
			</div>
			<div class="form-group">
				<label for="password" class="col-sm-3 control-label">##DS_LABEL5##</label>
				<div class="col-sm-7 form-inline">
					<input type="password" name="adminpass1" class="form-control" id="password" placeholder="##DS_PLACEHOLDE5##">&nbsp;<input type="password" name="adminpass2" class="form-control" id="password" placeholder="##DS_PLACEHOLDE6##"> <span id="helpBlock" class="help-block">##DS_HELP_BLOCK5##</span>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-7 col-sm-3">
					<button type="submit" name="submit" value="gameSettings" class="pull-right btn btn-primary btn-block">##SAVE_CONFIG##</button>
				</div>
			</div>
		</form>
	</div>
	{/if} </main>
</body>
</html>
