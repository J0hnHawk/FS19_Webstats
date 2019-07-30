<div class="container">
	<h3 class="mt-3">##INSTALL_TITLE## ##HEAD_API##</h3>
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