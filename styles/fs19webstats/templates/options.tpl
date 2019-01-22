<h3 class="my-3">##SETTINGS##</h3>
<div class="row">
	<div class="col-sm-12">
		{if $error}{$error}{/if}
		<form class="form-horizontal" action="index.php?page=options" method="post">
			<fieldset>
				<legend>##GENERAL##</legend>
				<div class="form-group">
					<label class="col-sm-3 control-label">##AUTO_RELOAD##</label>
					<div class="col-sm-9">
						<label class="radio-inline"> <input type="radio" name="g_reload" value="1"{if $options.general.reload}checked{/if}> ##YES##
						</label> <label class="radio-inline"> <input type="radio" name="g_reload" value="0"{if !$options.general.reload}checked{/if}> ##NO##
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">##CHOOSE_LANGUAGE_LABEL##</label>
					<div class="col-sm-4">
						<select class="form-control" name="g_language"> {foreach $languages as $language}
							<option value="{$language.path}" {if $language.path==$smarty.session.language}selected{/if}>{$language.localName}</option> {/foreach}
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">##CHOOSE_STYLE##</label>
					<div class="col-sm-4">
						<select class="form-control" name="g_style"> {foreach $styles as $styleData}
							<option value="{$styleData.path}" {if $styleData.path==$style}selected{/if}>{$styleData.name}</option> {/foreach}
						</select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">##HIDE_FOOTER##</label>
					<div class="col-sm-9">
						<label class="radio-inline"> <input type="radio" name="g_hideFooter" value="0"{if !$options.general.hideFooter}checked{/if}> ##SHOW##
						</label> <label class="radio-inline"> <input type="radio" name="g_hideFooter" value="1"{if $options.general.hideFooter}checked{/if}> ##HIDE##
						</label>
					</div>
				</div>
			</fieldset>
			<fieldset>
				<legend>##STOCKS##</legend>
				<div class="form-group">
					<label class="col-sm-3 control-label">##SORT_ORDER##</label>
					<div class="col-sm-9">
						<label class="radio-inline"> <input type="radio" name="s_sortByName" value="1"{if $options.storage.sortByName}checked{/if}> ##ALPHABETICALLY##
						</label> <label class="radio-inline"> <input type="radio" name="s_sortByName" value="0"{if !$options.storage.sortByName}checked{/if}>
							##FILL_LEVEL##
						</label>
					</div>
				</div>
				<div class="form-group">
					<label for="s_showVehicles" class="col-sm-3 control-label">##VEHICLE_LOAD##</label>
					<div class="col-sm-9">
						<label class="radio-inline"> <input type="radio" name="s_showVehicles" value="1"{if $options.storage.showVehicles}checked{/if}> ##SHOW##
						</label> <label class="radio-inline"> <input type="radio" name="s_showVehicles" value="0"{if !$options.storage.showVehicles}checked{/if}>
							##HIDE##
						</label>
					</div>
				</div>
				<div class="form-group">
					<label for="s_onlyPallets" class="col-sm-3 control-label">##ONLY_PALETTS##</label>
					<div class="col-sm-9">
						<label class="radio-inline"> <input type="radio" name="s_onlyPallets" value="1"{if $options.storage.onlyPallets}checked{/if}> ##YES##
						</label> <label class="radio-inline"> <input type="radio" name="s_onlyPallets" value="0"{if !$options.storage.onlyPallets}checked{/if}> ##NO##
						</label>
					</div>
				</div>
				<div class="form-group">
					<label for="s_hideZero" class="col-sm-3 control-label">##SHOW_ZERO_STOCK##</label>
					<div class="col-sm-9">
						<label class="radio-inline"> <input type="radio" name="s_hideZero" value="0"{if !$options.storage.hideZero}checked{/if}> ##YES##
						</label> <label class="radio-inline"> <input type="radio" name="s_hideZero" value="1"{if $options.storage.hideZero}checked{/if}> ##NO##
						</label>
					</div>
				</div>
				<div class="form-group">
					<label for="s_hideAnimalsInStorage" class="col-sm-3 control-label">##HIDE_ANIMALS##</label>
					<div class="col-sm-9">
						<label class="radio-inline"> <input type="radio" name="s_hideAnimalsInStorage" value="1"{if $options.storage.hideAnimalsInStorage}checked{/if}>
							##YES##
						</label> <label class="radio-inline"> <input type="radio" name="s_hideAnimalsInStorage" value="0"{if !$options.storage.hideAnimalsInStorage}checked{/if}>
							##NO##
						</label>
					</div>
				</div>
				<div class="form-group">
					<label for="s_3column" class="col-sm-3 control-label">##LAYOUT##</label>
					<div class="col-sm-9">
						<label class="radio-inline"> <input type="radio" name="s_3column" value="1"{if !$options.storage.hideZero}checked{/if}> ##3COLUMN##
						</label> <label class="radio-inline"> <input type="radio" name="s_3column" value="0"{if $options.storage.hideZero}checked{/if}> ##4COLUMN##
						</label>
					</div>
				</div>
			</fieldset>
			<fieldset>
				<legend>##PLANTS##</legend>
				<div class="form-group">
					<label for="sortType" class="col-sm-3 control-label">##SORT_ORDER##</label>
					<div class="col-sm-9">
						<label class="radio-inline"> <input type="radio" name="p_sortByName" value="1"{if $options.production.sortByName}checked{/if}>
							##ALPHABETICALLY##
						</label> <label class="radio-inline"> <input type="radio" name="p_sortByName" value="0"{if !$options.production.sortByName}checked{/if}>
							##FILL_LEVEL##
						</label>
					</div>
				</div>
				<div class="form-group">
					<label for="sortType" class="col-sm-3 control-label">##FULL_PRODUCT_STORAGE##</label>
					<div class="col-sm-9">
						<label class="radio-inline"> <input type="radio" name="p_sortFullProducts" value="1"{if $options.production.sortFullProducts}checked{/if}>
							##SORT_FULL_PRODUCTS##
						</label> <label class="radio-inline"> <input type="radio" name="p_sortFullProducts" value="0"{if !$options.production.sortFullProducts}checked{/if}>
							##IGNORE_FULL_PRODUCTS##
						</label>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">##TOOLTIP##</label>
					<div class="col-sm-9">
						<label class="radio-inline"> <input type="radio" name="p_showTooltip" value="1"{if $options.production.showTooltip}checked{/if}> ##YES##
						</label> <label class="radio-inline"> <input type="radio" name="p_showTooltip" value="0"{if !$options.production.showTooltip}checked{/if}>
							##NO##
						</label><span id="helpBlock" class="help-block">##TOOLTIP_HELP##</span>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">##HIDE_NOT_USED##</label>
					<div class="col-sm-9">
						<label class="radio-inline"> <input type="radio" name="p_hideNotUsed" value="1"{if $options.production.hideNotUsed}checked{/if}> ##YES##
						</label> <label class="radio-inline"> <input type="radio" name="p_hideNotUsed" value="0"{if !$options.production.hideNotUsed}checked{/if}>
							##NO##
						</label><span id="helpBlock" class="help-block">##HIDE_NOT_USED_HELP##</span>
					</div>
				</div>
			</fieldset>
			<fieldset>
				<legend></legend>
				<div class="form-group">
					<div class="col-sm-12">
						<button type="button" data-toggle="modal" data-target="#password_check" class="btn btn-danger">##SERVER_SETTINGS##</button>
						<button type="submit" class="btn btn-success pull-right" name="submit" value="options">##SAVE##</button>
						<button type="reset" class="btn btn-default pull-right">##RESET##</button>
					</div>
				</div>
			</fieldset>
		</form>
	</div>
</div>
<div class="modal fade" id="password_check" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<form action="index.php?page=options" method="post">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<h4 class="modal-title" id="myModalLabel">##ADMIN_AREA##</h4>
				</div>
				<div class="modal-body">
					<p>##ADMIN_DESCRIPTION##</p>
					<p>##ENTER_PASSWORD##</p>
					<label for="inputPassword" class="sr-only">Password</label> <input type="password" name="adminpass1" id="inputPassword" class="form-control"
						placeholder="##DS_PLACEHOLDE5##" required autofocus>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">##CLOSE##</button>
					<button type="submit" class="btn btn-warning" name="submit" value="password">##SUBMIT##</button>
				</div>
			</div>
		</form>
	</div>
</div>