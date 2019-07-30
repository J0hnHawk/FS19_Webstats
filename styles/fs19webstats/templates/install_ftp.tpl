<div class="container">
	<h3 class="mt-3">##INSTALL_TITLE## ##HEAD_FTP##</h3>
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
			<div class="col-sm-3">##FTPOPTIONS##</div>
			<div class="col-sm-7">
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="checkbox" id="ftpssl" name="ftpssl"> <label class="form-check-label pl-1" for="ftpssl">##FTPSSLCON##</label>
				</div>
				<div class="form-check form-check-inline">
					<input class="form-check-input" type="checkbox" id="ftpgportal" name="ftpgportal"> <label class="form-check-label pl-1" for="ftpgportal">G-Portal Game Server</label>
				</div>
			</div>
		</div>
		<div class="form-group row">
			<label for="ftppath" class="col-sm-3 col-form-label">##FTPPATH##</label>
			<div class="col-sm-7">
				<input type="text" class="form-control" id="ftppath" name="ftppath" placeholder="/folder/subfolder/">
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