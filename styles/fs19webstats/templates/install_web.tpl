<div class="container">
	<h3 class="mt-3">##INSTALL_TITLE## ##HEAD_WEB##</h3>
	<form class="form-horizontal" action="index.php?mode={$mode}" method="post">
		{if $fsockopen} {if $error}{$error}{/if}
		<div class="form-group">
			<label for="weburl" class="col-sm-3 control-label">##WEB_URL##</label>
			<div class="col-sm-7">
				<div class="input-group">
					<input type="ip" name="weburl" class="form-control" id="weburl" placeholder="https://adress.of.your.server:port/index.html?lang=de">
					<div class="input-group-append">
						<button class="btn btn-outline-secondary" type="button" data-toggle="popover" data-html="true" data-content="<img src='{#IMAGES#}/install_webadress.png'>">##HELP##</button>
					</div>
				</div>
				<small id="weburlHelp" class="form-text text-muted">##WEB_URL_HELP_BLOCK##</small>
			</div>
		</div>
		<div class="form-group">
			<label for="webuser" class="col-sm-3 control-label">##WEB_USER##</label>
			<div class="col-sm-7">
				<div class="input-group">
					<input type="text" name="webuser" class="form-control" id="webuser" placeholder="fs19webstats">
					<div class="input-group-append">
						<button class="btn btn-outline-secondary" type="button" data-toggle="popover" data-html="true" data-content="<img src='{#IMAGES#}/install_webuser.png'>">##HELP##</button>
					</div>
				</div>
				<small id="webuserHelp" class="form-text text-muted">##WEB_USER_HELP_BLOCK##</small>
			</div>
		</div>
		<div class="form-group">
			<label for="webpass" class="col-sm-3 control-label">##WEB_PASS##</label>
			<div class="col-sm-7">
				<input type="text" name="webpass" class="form-control" id="webpass"> <small id="webpassHelp" class="form-text text-muted">##WEB_PASS_HELP_BLOCK##</small>
			</div>
		</div>
		<div class="form-group">
			<label for="webslot" class="col-sm-3 control-label">##WEB_SLOT##</label>
			<div class="col-sm-7">
				<select class="custom-select" id="webslot" name="webslot"> {for $slot=1 to 20}
					<option value="savegame{$slot}">Savegame {$slot}</option> {/for}
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-3 control-label">##DS_LABEL4##</label>
			<div class="col-sm-7">
				<select class="custom-select" id="map" name="map"> {foreach $maps as $mapDir => $mapData}
					<option value="{$mapDir}">{$mapData.Name} {$mapData.Version}</option> {/foreach}
				</select><small id="mapHelp" class="form-text text-muted">##DS_HELP_BLOCK4##</small>
			</div>
		</div>
		<div class="form-group">
			<label for="password" class="col-sm-3 control-label">##DS_LABEL5##</label>
			<div class="col-sm-7 form-inline">
				<input type="password" name="adminpass1" class="form-control" id="password" placeholder="##DS_PLACEHOLDE5##">&nbsp;<input type="password" name="adminpass2" class="form-control" id="password" placeholder="##DS_PLACEHOLDE6##"> <small id="passwordHelp" class="form-text text-muted">##DS_HELP_BLOCK5##</small>
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