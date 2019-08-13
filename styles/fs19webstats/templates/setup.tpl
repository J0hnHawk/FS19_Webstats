{config_load file='../style.cfg'}
<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<meta name="description" content="Farming Simulator 19 Web Stats">
<meta name="author" content="John Hawk">
<link rel="icon" href="{#IMAGES#}/favicon.ico">
<title>FS19 Web Stats</title>
<link href="{#CSS#}/bootstrap.min.css" rel="stylesheet">
<link href="{#CSS#}/theme.min.css" rel="stylesheet">
<link href="{#CSS#}/customstyle.css" rel="stylesheet">
<script src="{#SCRIPTS#}/jquery.min.js"></script>
<script src="{#SCRIPTS#}/bootstrap.min.js"></script>
</head>
<body class="d-flex flex-column h-100 bg-dark">
	<!-- bg-dark -->
	<main role="main" class="flex-shrink-0">
	<div class="container">
		<div class="row">
			<div class="col">
				<img src="{#IMAGES#}/webStatsLogo.png" class="img-fluid">
			</div>
		</div>
		{if $success}
		<div class="row">
			<div class="col mt-5">
				<p class="lead text-light">##CONFIG_SAVED##</p>
				<p>
					<a class="btn btn-primary btn-lg" href="index.php" role="button">##CONTINUE## &raquo;</a>
				</p>
			</div>
		</div>
		{else} {if $mode=="language"}
		<div class="row justify-content-center">
			{foreach $languages as $language}
			<div class="col-4 col-sm-2 col-md-2 mt-5">
				<a href="index.php?mode=language&selected={$language.path}">
					<img src="./language/{$language.path}/flag.png" class="img-fluid">
					<h5 class="text-light text-center mt-2">{$language.localName}</h5>
				</a>
			</div>
			{/foreach}
		</div>
		{else if $mode=="savegame_type"}
		<div class="row">
			<div class="col mt-5">
				<p class="lead text-light">##WEBSTATS_DESCRIPTON##</p>
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col-sm-4 mt-5">
				<h4 class="text-light text-center">##DEDICATED##</h4>
				<img src="{#IMAGES#}/install/dedicated.png" class="img-fluid w-50 mx-auto d-block">
				<p class="text-light mt-3">##DEDICATED_DESCRIPTON##</p>
				<a href="index.php?mode=server" class="btn btn-primary float-right">##NEXT##</a>
			</div>
			<div class="col-sm-4 mt-5">
				<h4 class="text-light text-center">##LOCAL_SAVEGAME##</h4>
				<img src="{#IMAGES#}/install/local.png" class="img-fluid w-50 mx-auto d-block">
				<p class="text-light mt-3">##LOCAL_SAVEGAME_DESCRIPTON##</p>
				<a href="index.php?mode=local" class="btn btn-primary float-right">##NEXT##</a>
			</div>
		</div>
		{else if $mode=="server"}
		<div class="row">
			<div class="col mt-3">
				<h3 class="text-light">##DEDICATED##</h3>
				<form class="text-light" action="index.php?mode={$mode}" method="post">
					{if $fsockopen} {if array_sum($error)}
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<h4 class="alert-heading">##ERROR##</h4>
						<ul>
							{if $error.link_xml}
							<li>##LINK_XML_ERROR##</li>{/if} {if $error.webinterface_url}
							<li>##WEBINTERFACE_URL_ERROR##</li>{/if} {if $error.webinterface_username}
							<li>##WEBINTERFACE_USERNAME_ERROR##</li>{/if} {if $error.savegame_slot}
							<li>##SAVEGAME_ERROR##</li>{/if} {if $error.map}
							<li>##MAP_ERROR##</li>{/if} {if $error.webstatsPassword}
							<li>##WEBSTATS_PASSWORD_ERROR##</li>{/if} {if $error.webstatsPasswordRepeat}
							<li>##WEBSTATS_PASSWORD_REPEAT_ERROR##</li>{/if}
						</ul>
					</div>
					{/if}
					<div class="form-group row">
						<label for="link_xml" class="col-3 col-form-label">##LINK_XML##</label>
						<div class="col-9">
							<div class="input-group">
								<input id="link_xml" name="link_xml" placeholder="##LINK_XML_PLACEHOLDER##" class="form-control" type="text" value="{$config.link_xml}">
								<div class="input-group-append">
									<button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#helpImageModal" data-image="link_xml.png">##HELP##</button>
								</div>
							</div>
							<span id="link_xmlHelpBlock" class="form-text text-muted">##LINK_XML_HELP##</span>
						</div>
					</div>
					<div class="form-group row">
						<label for="webinterface_url" class="col-3 col-form-label">##WEBINTERFACE_URL##</label>
						<div class="col-9">
							<div class="input-group">
								<input id="webinterface_url" name="webinterface_url" placeholder="##WEBINTERFACE_URL_PLACEHOLDER##" class="form-control" type="text"
									value="{$config.webinterface_url}">
								<div class="input-group-append">
									<button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#helpImageModal" data-image="webadress.png">##HELP##</button>
								</div>
							</div>
							<span id="url_webinterfaceHelpBlock" class="form-text text-muted">##WEBINTERFACE_URL_HELP##</span>
						</div>
					</div>
					<div class="form-group row">
						<label for="webinterface_username" class="col-3 col-form-label">##WEBINTERFACE_USERNAME##</label>
						<div class="col-9">
							<div class="input-group">
								<input id="webinterface_username" name="webinterface_username" placeholder="fs19webstats" class="form-control" type="text"
									value="{$config.webinterface_username}">
								<div class="input-group-append">
									<button class="btn btn-outline-secondary" type="button" data-toggle="modal" data-target="#helpImageModal" data-image="webuser.png">##HELP##</button>
								</div>
							</div>
							<span id="webinterface_usernameHelpBlock" class="form-text text-muted">##WEBINTERFACE_USERNAME_HELP##</span>
						</div>
					</div>
					<div class="form-group row">
						<label for="webinterface_password" class="col-3 col-form-label">##WEBINTERFACE_PASSWORD##</label>
						<div class="col-9">
							<input id="webinterface_password" name="webinterface_password" placeholder="##WEBINTERFACE_PASSWORD_PLACEHOLDER##" class="form-control"
								type="text" value="{$config.webinterface_password}"> <span id="webinterface_passwordHelpBlock" class="form-text text-muted">##WEBINTERFACE_PASSWORD_HELP##</span>
						</div>
					</div>
					<div class="form-group row">
						<label for="savegame_slot" class="col-3 col-form-label">##SAVEGAME##</label>
						<div class="col-9">
							<select id="savegame_slot" name="savegame_slot" class="custom-select"> {for $slot=1 to 20}{$value="savegame"|cat:$slot} {if
								$config.savegame_slot==$value}{$selected=" selected"}{else}{$selected=""}{/if}
								<option value="{$value}"{$selected}>Savegame {$slot}</option> {/for}
							</select> <span id="savegame_slotHelpBlock" class="form-text text-muted">##SAVEGAME_HELP##</span>
						</div>
					</div>
					<div class="form-group row">
						<label for="map" class="col-3 col-form-label">##MAP##</label>
						<div class="col-9">
							<select id="map" name="map" class="custom-select" aria-describedby="mapHelpBlock"> {foreach $maps as $mapDir => $mapData}{if
								$config.map==$mapDir}{$selected=" selected"}{else}{$selected=""}{/if}
								<option value="{$mapDir}"{$selected}>{$mapData.Name} {$mapData.Version}</option> {/foreach}
							</select> <span id="mapHelpBlock" class="form-text text-muted">##MAP_HELP##</span>
						</div>
					</div>
					<div class="form-group row">
						<label for="webstatsPassword" class="col-3 col-form-label">##WEBSTATS_PASSWORD##</label>
						<div class="col-9 form-inline">
							<input id="webstatsPassword" name="webstatsPassword" placeholder="##WEBSTATS_PASSWORD_PLACEHOLDER##" class="form-control col" type="password"
								value="{$config.webstatsPassword}">&nbsp;<input id="webstatsPasswordRepeat" name="webstatsPasswordRepeat"
								placeholder="##WEBSTATS_PASSWORD_REPEAT_PLACEHOLDER##" class="form-control col" type="password" value="{$config.webstatsPasswordRepeat}"> <span
								id="webstatsPasswordHelpBlock" class="form-text text-muted">##WEBSTATS_PASSWORD_HELP##</span>
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-3 col-9">
							<button name="submit" type="submit" class="btn btn-primary">##SAVE_CONFIG##</button>
						</div>
					</div>
					{else}
					<p class="lead">##NO_INSTALL_1##</p>
					<p class="lead">##NO_INSTALL_2##</p>
					{/if}
				</form>
			</div>
		</div>
		<!--  Modal help images -->
		<div class="modal fade" tabindex="-1" role="dialog" id="helpImageModal">
			<div class="modal-dialog modal-lg modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-body">
						<img id="helpImage" src="" class="img-fluid w-100 mx-auto d-block">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">##CLOSE##</button>
					</div>
				</div>
			</div>
		</div>
		<script>
			$('#helpImageModal').on('show.bs.modal', function (event) {
  				var button = $(event.relatedTarget) // Button that triggered the modal
  				var image = button.data('image') // Extract info from data-* attributes
				$('#helpImage').attr("src", "{#IMAGES#}/install/" + image )
			})
		</script>
		{else if $mode=="local"}
		<div class="row">
			<div class="col mt-3">
				<h3 class="text-light">##LOCAL_SAVEGAME##</h3>
				<form class="text-light" action="index.php?mode={$mode}" method="post">
					{if array_sum($error)}
					<div class="alert alert-danger alert-dismissible fade show" role="alert">
						<h4 class="alert-heading">##ERROR##</h4>
						<ul>
							{if $error.fs19path}
							<li>##PATH_TO_FS19_ERROR##</li>{/if} {if $error.savegame_slot}
							<li>##SAVEGAME_ERROR##</li>{/if} {if $error.map}
							<li>##MAP_ERROR##</li>{/if} {if $error.webstatsPassword}
							<li>##WEBSTATS_PASSWORD_ERROR##</li>{/if} {if $error.webstatsPasswordRepeat}
							<li>##WEBSTATS_PASSWORD_REPEAT_ERROR##</li>{/if}
						</ul>
					</div>
					{/if}
					<p>##LOCAL_SAVEGAME_INTRO##</p>
					<div class="form-group row">
						<label for="fs19path" class="col-3 col-form-label">##PATH_TO_FS19##</label>
						<div class="col-9">
							<input id="fs19path" name="fs19path" placeholder="##PATH_TO_FS19_PLACEHOLDER##" class="form-control" aria-describedby="fs19pathHelpBlock"
								type="text" value="{$config.fs19path}"> <span id="fs19pathHelpBlock" class="form-text text-muted">##PATH_TO_FS19_HELP##</span>
						</div>
					</div>
					<div class="form-group row">
						<label for="savegame_slot" class="col-3 col-form-label">##SAVEGAME##</label>
						<div class="col-9">
							<select id="savegame_slot" name="savegame_slot" class="custom-select"> {for $slot=1 to 20}{$value="savegame"|cat:$slot} {if
								$config.savegame_slot==$value}{$selected=" selected"}{else}{$selected=""}{/if}
								<option value="{$value}"{$selected}>Savegame {$slot}</option> {/for}
							</select> <span id="savegame_slotHelpBlock" class="form-text text-muted">##SAVEGAME_HELP##</span>
						</div>
					</div>
					<div class="form-group row">
						<label for="map" class="col-3 col-form-label">##MAP##</label>
						<div class="col-9">
							<select id="map" name="map" class="custom-select" aria-describedby="mapHelpBlock"> {foreach $maps as $mapDir => $mapData}{if
								$config.map==$mapDir}{$selected=" selected"}{else}{$selected=""}{/if}
								<option value="{$mapDir}"{$selected}>{$mapData.Name} {$mapData.Version}</option> {/foreach}
							</select> <span id="mapHelpBlock" class="form-text text-muted">##MAP_HELP##</span>
						</div>
					</div>
					<div class="form-group row">
						<label for="webstatsPassword" class="col-3 col-form-label">##WEBSTATS_PASSWORD##</label>
						<div class="col-9 form-inline">
							<input id="webstatsPassword" name="webstatsPassword" placeholder="##WEBSTATS_PASSWORD_PLACEHOLDER##" class="form-control col" type="password"
								value="{$config.webstatsPassword}">&nbsp;<input id="webstatsPasswordRepeat" name="webstatsPasswordRepeat"
								placeholder="##WEBSTATS_PASSWORD_REPEAT_PLACEHOLDER##" class="form-control col" type="password" value="{$config.webstatsPasswordRepeat}"> <span
								id="webstatsPasswordHelpBlock" class="form-text text-muted">##WEBSTATS_PASSWORD_HELP##</span>
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-3 col-9">
							<button name="submit" type="submit" class="btn btn-primary">##SAVE_CONFIG##</button>
						</div>
					</div>
				</form>
			</div>
		</div>
		{else}
		<div class="row">
			<div class="col mt-5">
				<p class="lead text-light">
					<a href="index.php">##ERROR##</a>
				</p>
			</div>
		</div>
		{/if} {/if}
	</div>
	</main>
	<footer class="footer mt-auto py-3">
		<div class="container">
			<div class="text-center text-muted">
				Icons made by
				<a href="https://www.flaticon.com/authors/freepik" title="Freepik">Freepik</a>
				from
				<a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a>
				is licensed by
				<a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a>
			</div>
		</div>
	</footer>
</body>
</html>