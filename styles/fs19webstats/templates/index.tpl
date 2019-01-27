{config_load file='../style.cfg'}
<html lang="de">
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
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
<script type="text/javascript" src="{#SCRIPTS#}/jquery.min.js"></script>
<script type="text/javascript" src="{#SCRIPTS#}/bootstrap.min.js"></script>
<script type="text/javascript" src="{#SCRIPTS#}/datatables.min.js"></script>
</head>
<body>
	<header>
		{include file='navbar.tpl'}
	</header>
	<div class="container" style="padding-left: 10px; padding-right: 10px">
		{if $serverOnline}{assign var="fullPathToTemplate" value="./styles/$style/templates/$page.tpl"} {if file_exists($fullPathToTemplate)} {include file="$page.tpl"} {else}
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
				&copy; 2018-2019 FS19 Web Stats by John Hawk
			</p>
		</div>
	</div>
	{/if}
	<script type="text/javascript">
	function clickDropdown() {
		if ($('.navbar-toggler').css("display") === "none") {
			$('.dropdown-toggle').click(function () {
	            window.location.href = $(this).attr('href');
	            return false;
	        });
	    }
	}    
	$(document).ready(function () {
	    clickDropdown();
	    $(window).resize(function () {
	        clickDropdown();
	    });
	});
	{if $reloadPage && $serverOnline}
	var time = new Date().getTime();
	$(document.body).bind("mousemove keypress", function () {
	    time = new Date().getTime();
	});

	setInterval(function() {
	    if (new Date().getTime() - time >= 60000) {
	        window.location.reload(true);
	    }
	}, 1000);
	{/if}
	</script>
</body>
</html>