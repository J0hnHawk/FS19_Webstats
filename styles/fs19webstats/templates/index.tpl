{config_load file='../style.cfg'}
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="Farming Simulator 19 Web Stats">
<meta name="author" content="John Hawk">
<link rel="icon" href="{#IMAGES#}/favicon.ico">
<title>{$map.Short} {$map.Version} Web Stats</title>
<link rel="stylesheet" type="text/css" href="{#CSS#}/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="{#CSS#}/theme.min.css?{$smarty.now}">
<link rel="stylesheet" type="text/css" href="{#CSS#}/customstyle.css?{$smarty.now}">
<link rel="stylesheet" type="text/css" href="{#CSS#}/datatables.min.css" />
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css"
	integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
<script type="text/javascript" src="{#SCRIPTS#}/jquery.min.js"></script>
<script type="text/javascript" src="{#SCRIPTS#}/bootstrap.min.js"></script>
<script type="text/javascript" src="{#SCRIPTS#}/datatables.min.js"></script>
</head>
<body>
	<header>
		<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark justify-content-center">
			<a class="navbar-brand" href="#">Navbar</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false"
				aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse justify-content-between align-items-center" id="navbarNav">
				<ul class="navbar-nav mx-auto text-center">
					<li class="nav-item active"><a class="nav-link" href="#">Übersicht</a></li>
					<li class="nav-item"><a class="nav-link" href="#">Preise</a></li>
					<li class="nav-item"><a class="nav-link" href="#">Fahrzeuge</a></li>
					<li class="nav-item"><a class="nav-link" href="#">Finanzen</a></li>
					<li class="nav-item"><a class="nav-link" href="#">Tiere</a></li>
					<li class="nav-item"><a class="nav-link" href="#">Aufträge</a></li>
					<li class="nav-item"><a class="nav-link" href="#">Farm</a></li>
					<li class="nav-item"><a class="nav-link" href="#">Statistik</a></li>
				</ul>
				<span class="navbar-text text-right">Uhrzeit | Kontostand</span>
			</div>
		</nav>
	</header>
	<div class="container" style="padding-left: 10px; padding-right: 10px">
		{if $serverOnline}{assign var="fullPathToTemplate" value="./styles/$style/templates/$page.tpl"} {if file_exists($fullPathToTemplate)} {include
		file="$page.tpl"} {else}
		<div class="container theme-showcase" role="main">
			<div class="jumbotron">
				<h1>##TPL_ERROR_1##</h1>
				<p class="lead">##TPL_ERROR_2## {$fullPathToTemplate} ##TPL_ERROR_3##</p>
			</div>
		</div>
		{/if}{else}
		<div class="container theme-showcase" role="main">
			<div class="jumbotron">
				<h1>##CON_ERROR_1##</h1>
				<p class="lead">##CON_ERROR_2##</p>
			</div>
		</div>
		{/if}
	</div>
	{if !$hideFooter}
	<div class="navbar navbar-default navbar-fixed-bottom hidden-xs">
		<div class="container">
			<p class="navbar-text text-center">{$onlineUser} ##USER_ONLINE##</p>
			<p class="navbar-text pull-right">
				&copy; 2018 FS19 Web Stats by John Hawk &bull; <a href="https://github.com/J0hnHawk/FS19_WebStats" target="_blank">GitHub</a> &bull; Map config by
				{$map.configBy}
			</p>
		</div>
	</div>
	{/if}
</body>
</html>