<style>
.custom-select {
	background: none !important;
}
</style>
{if $error} {$error} {/if}
<h3 class="my-3">##SETTINGS## - ##GENERAL##</h3>
<form class="form" action="index.php?page=options" method="post">
	<div class="row">
		<div class="col-md-6 col-lg-4">
			<label for="basic-url">##CHOOSE_LANGUAGE_LABEL##</label>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<button class="btn btn-outline-secondary back-button" type="button" data-id="g_language">&#11207;</button>
				</div>
				<select class="custom-select text-center" name="g_language" id="g_language">{foreach $languages as $language}
					<option value="{$language.path}" {if $language.path==$smarty.session.language}selected{/if}>{$language.localName}</option> {/foreach}
				</select>
				<div class="input-group-append">
					<button class="btn btn-outline-secondary next-button" type="button" data-id="g_language">&#11208;</button>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-4">
			<label for="basic-url">##CHOOSE_STYLE##</label>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<button class="btn btn-outline-secondary back-button" type="button" data-id="g_style">&#11207;</button>
				</div>
				<select class="custom-select text-center" name="g_style" id="g_style">{foreach $styles as $styleData}
					<option value="{$styleData.path}" {if $styleData.path==$style}selected{/if}>{$styleData.name}</option> {/foreach}
				</select>
				<div class="input-group-append">
					<button class="btn btn-outline-secondary next-button" type="button" data-id="g_style">&#11208;</button>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-4">
			<label for="basic-url">##AUTO_RELOAD##</label>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<button class="btn btn-outline-secondary back-button" type="button" data-id="g_reload">&#11207;</button>
				</div>
				<select class="custom-select text-center" name="g_reload" id="g_reload">
					<option value="1"{if $options.general.reload}selected{/if}>##YES##</option>
					<option value="0"{if !$options.general.reload}selected{/if}>##NO##</option>
				</select>
				<div class="input-group-append">
					<button class="btn btn-outline-secondary next-button" type="button" data-id="g_reload">&#11208;</button>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-4">
			<label for="basic-url">##HIDE_FOOTER##</label>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<button class="btn btn-outline-secondary back-button" type="button" data-id="g_hideFooter">&#11207;</button>
				</div>
				<select class="custom-select text-center" name="g_hideFooter" id="g_hideFooter">
					<option value="1"{if $options.general.hideFooter}selected{/if}>##HIDE##</option>
					<option value="0"{if !$options.general.hideFooter}selected{/if}>##SHOW##</option>
				</select>
				<div class="input-group-append">
					<button class="btn btn-outline-secondary next-button" type="button" data-id="g_hideFooter">&#11208;</button>
				</div>
			</div>
		</div>
	</div>
	<h3 class="my-3">##SETTINGS## - ##STOCKS##</h3>
	<div class="row">
		<div class="col-md-6 col-lg-4">
			<label for="basic-url">##SORT_ORDER##</label>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<button class="btn btn-outline-secondary back-button" type="button" data-id="s_sortByName">&#11207;</button>
				</div>
				<select class="custom-select text-center" name="s_sortByName" id="s_sortByName">
					<option value="1"{if $options.storage.sortByName}selected{/if}>##ALPHABETICALLY##</option>
					<option value="0"{if !$options.storage.sortByName}selected{/if}>##FILL_LEVEL##</option>
				</select>
				<div class="input-group-append">
					<button class="btn btn-outline-secondary next-button" type="button" data-id="s_sortByName">&#11208;</button>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-4">
			<label for="basic-url">##VEHICLE_LOAD##</label>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<button class="btn btn-outline-secondary back-button" type="button" data-id="s_showVehicles">&#11207;</button>
				</div>
				<select class="custom-select text-center" name="s_showVehicles" id="s_showVehicles">
					<option value="1"{if $options.storage.showVehicles}selected{/if}>##SHOW##</option>
					<option value="0"{if !$options.storage.showVehicles}selected{/if}>##HIDE##</option>
				</select>
				<div class="input-group-append">
					<button class="btn btn-outline-secondary next-button" type="button" data-id="s_showVehicles">&#11208;</button>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-4">
			<label for="basic-url">##ONLY_PALETTS##</label>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<button class="btn btn-outline-secondary back-button" type="button" data-id="s_onlyPallets">&#11207;</button>
				</div>
				<select class="custom-select text-center" name="s_onlyPallets" id="s_onlyPallets">
					<option value="1"{if $options.storage.onlyPallets}selected{/if}>##YES##</option>
					<option value="0"{if !$options.storage.onlyPallets}selected{/if}>##NO##</option>
				</select>
				<div class="input-group-append">
					<button class="btn btn-outline-secondary next-button" type="button" data-id="s_onlyPallets">&#11208;</button>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-4">
			<label for="basic-url">##SHOW_ZERO_STOCK##</label>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<button class="btn btn-outline-secondary back-button" type="button" data-id="s_hideZero">&#11207;</button>
				</div>
				<select class="custom-select text-center" name="s_hideZero" id="s_hideZero">
					<option value="1"{if $options.storage.hideZero}selected{/if}>##YES##</option>
					<option value="0"{if !$options.storage.hideZero}selected{/if}>##NO##</option>
				</select>
				<div class="input-group-append">
					<button class="btn btn-outline-secondary next-button" type="button" data-id="s_hideZero">&#11208;</button>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-4">
			<label for="basic-url">##HIDE_ANIMALS##</label>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<button class="btn btn-outline-secondary back-button" type="button" data-id="s_hideAnimalsInStorage">&#11207;</button>
				</div>
				<select class="custom-select text-center" name="s_hideAnimalsInStorage" id="s_hideAnimalsInStorage">
					<option value="1"{if $options.storage.hideAnimalsInStorage}selected{/if}>##YES##</option>
					<option value="0"{if !$options.storage.hideAnimalsInStorage}selected{/if}>##NO##</option>
				</select>
				<div class="input-group-append">
					<button class="btn btn-outline-secondary next-button" type="button" data-id="s_hideAnimalsInStorage">&#11208;</button>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-4">
			<label for="basic-url">##LAYOUT##</label>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<button class="btn btn-outline-secondary back-button" type="button" data-id="s_3column">&#11207;</button>
				</div>
				<select class="custom-select text-center" name="s_3column" id="s_3column">
					<option value="1"{if $options.storage.3column}selected{/if}>##3COLUMN##</option>
					<option value="0"{if !$options.storage.3column}selected{/if}>##4COLUMN##</option>
				</select>
				<div class="input-group-append">
					<button class="btn btn-outline-secondary next-button" type="button" data-id="s_3column">&#11208;</button>
				</div>
			</div>
		</div>
	</div>
	<h3 class="my-3 d-none">##SETTINGS## - ##PLANTS##</h3>
	<div class="row d-none">
		<div class="col-md-6 col-lg-4">
			<label for="basic-url">##SORT_ORDER##</label>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<button class="btn btn-outline-secondary back-button" type="button" data-id="p_sortByName">&#11207;</button>
				</div>
				<select class="custom-select text-center" name="p_sortByName" id="p_sortByName">
					<option value="1"{if $options.production.sortByName}selected{/if}>##ALPHABETICALLY##</option>
					<option value="0"{if !$options.production.sortByName}selected{/if}>##FILL_LEVEL##</option>
				</select>
				<div class="input-group-append">
					<button class="btn btn-outline-secondary next-button" type="button" data-id="p_sortByName">&#11208;</button>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-4">
			<label for="basic-url">##FULL_PRODUCT_STORAGE##</label>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<button class="btn btn-outline-secondary back-button" type="button" data-id="p_sortFullProducts">&#11207;</button>
				</div>
				<select class="custom-select text-center" name="p_sortFullProducts" id="p_sortFullProducts">
					<option value="1"{if $options.production.sortFullProducts}selected{/if}>##SORT_FULL_PRODUCTS##</option>
					<option value="0"{if !$options.production.sortFullProducts}selected{/if}>##IGNORE_FULL_PRODUCTS##</option>
				</select>
				<div class="input-group-append">
					<button class="btn btn-outline-secondary next-button" type="button" data-id="p_sortFullProducts">&#11208;</button>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-4">
			<label for="basic-url">##TOOLTIP##</label>
			<div class="input-group mb-3 hoverhelp" data-helptext="##TOOLTIP_HELP##">
				<div class="input-group-prepend">
					<button class="btn btn-outline-secondary back-button" type="button" data-id="p_showTooltip">&#11207;</button>
				</div>
				<select class="custom-select text-center" name="p_showTooltip" id="p_showTooltip">
					<option value="1"{if $options.production.showTooltip}selected{/if}>##YES##</option>
					<option value="0"{if !$options.production.showTooltip}selected{/if}>##NO##</option>
				</select>
				<div class="input-group-append">
					<button class="btn btn-outline-secondary next-button" type="button" data-id="p_showTooltip">&#11208;</button>
				</div>
			</div>
		</div>
		<div class="col-md-6 col-lg-4">
			<label for="basic-url">##HIDE_NOT_USED##</label>
			<div class="input-group mb-3 hoverhelp" data-helptext="##HIDE_NOT_USED_HELP##">
				<div class="input-group-prepend">
					<button class="btn btn-outline-secondary back-button" type="button" data-id="p_hideNotUsed">&#11207;</button>
				</div>
				<select class="custom-select text-center" name="p_hideNotUsed" id="p_hideNotUsed">
					<option value="1"{if $options.production.hideNotUsed}selected{/if}>##YES##</option>
					<option value="0"{if !$options.production.hideNotUsed}selected{/if}>##NO##</option>
				</select>
				<div class="input-group-append">
					<button class="btn btn-outline-secondary next-button" type="button" data-id="p_hideNotUsed">&#11208;</button>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col">
			<label for="basic-url">&nbsp;</label>
			<div class="input-group mb-3">
				<div class="input-group-prepend">
					<span class="input-group-text">i</span>
				</div>
				<input type="text" class="form-control" id="helpRow">
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<button type="button" data-toggle="modal" data-target="#password_check" class="btn btn-danger">##SERVER_SETTINGS##</button>
			<button type="submit" class="btn btn-success float-right" name="submit" value="options">##SAVE##</button>
			<button type="reset" class="btn btn-default float-right mr-2">##RESET##</button>
		</div>
	</div>
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
					<label for="inputPassword" class="sr-only">Password</label> <input type="password" name="adminpass1" id="inputPassword" class="form-control" placeholder="##DS_PLACEHOLDE5##" required autofocus>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">##CLOSE##</button>
					<button type="submit" class="btn btn-warning" name="submit" value="password">##SUBMIT##</button>
				</div>
			</div>
		</form>
	</div>
</div>
<script>
$(".next-button").click(function() {
	id = ($(this).data("id"));
	var nextElement = $('#' + id + ' > option:selected').next('option');
	if (nextElement.length > 0) {
		$('#' + id + ' > option:selected').removeAttr('selected').next('option').attr('selected', 'selected');
	}
})
$(".back-button").click(function() {
	id = ($(this).data("id"));
	var nextElement = $('#' + id + ' > option:selected').prev('option');
	if (nextElement.length > 0) {
		$('#' + id + ' > option:selected').removeAttr('selected').prev('option').attr('selected', 'selected');
	}
});
$(".hoverhelp").hover(function() {
	text = ($(this).data("helptext"));
	$('#helpRow').val(text);
}, function () {
	$('#helpRow').val('');
})

</script>