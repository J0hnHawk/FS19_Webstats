<div class="container">
	<h3 class="mt-3">##INSTALL_TITLE## ##HEAD_LOCAL##</h3>
	<form class="form-horizontal" action="index.php?mode={$mode}" method="post">
		{if $error}{$error}{/if}
		<p>##INTRO_LOCAL##</p>
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