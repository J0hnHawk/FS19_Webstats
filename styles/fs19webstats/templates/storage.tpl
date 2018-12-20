<h3 class="mt-3">
	##STOCKS##<small class="text-right">{if $outOfMap|@count>0}</span><a href="#" data-toggle="modal" data-target="#outOfMapAlert"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> ##CAUTION##</a>&nbsp;&nbsp;{/if}<a href="#" data-toggle="modal" data-target="#optionsDialog"><span
			class="glyphicon glyphicon-cog" aria-hidden="true"></span> ##SETTINGS##</a></small>
</h3>
<div class="row">
	{if $options.3column} {$class="col-sm-4"} {$end=2} {$colmax[0] = -1} {$colmax[1] = ($commodities|@count/3)|ceil} {$colmax[2] = $colmax[1] *2} {$colmax[3] = $commodities|@count} {else} {$class="col-sm-3"} {$end=3} {$colmax[0] = -1} {$colmax[1] = ($commodities|@count/4)|ceil} {$colmax[2] = $colmax[1]
	*2} {$colmax[3] = $colmax[1] *3} {$colmax[4] = $commodities|@count} {/if} {for $i=0 to $end}
	<div class="{$class}">
		<table class="table table-hover table-striped">
			<thead>
				<tr>
					<th>##COMMODITY##</th>
					<th class="text-right">##STOCK##</th>
				</tr>
			</thead>
			<tbody>
				{foreach $commodities as $fillType => $commodity} {if $commodity@iteration > $colmax[$i] && $commodity@iteration <= $colmax[$i+1] }
				<tr data-toggle="collapse" href="#collapse{$commodity.i3dName}" {if isset($commodity.outOfMap)}class="danger"{/if}>
					<td>{$fillType}</td>
					<td class="text-right">{$commodity.overall|number_format:0:",":"."}</td>
				</tr>
				{if $commodity.overall>-1}
				<tr class="collapse" id="collapse{$commodity.i3dName}">
					<td colspan="3">
						<table class="table" style="margin-bottom: 0px;">
							<thead>
								<tr>
									<th>##PLACE##<a class="pull-right" href="index.php?page=commodity&object={$commodity.i3dName}">##DETAILS##</a></th>
									<th class="text-right">##QUANTITY##</th>
								</tr>
							</thead>
							<tbody>
								{foreach $commodity.locations as $locationName => $location} {$addInfo=false} {if isset($location.FillablePallet)} {if $location.FillablePallet==1} {$addInfo="1 ##PALLET##"} {else} {$addInfo="{$location.FillablePallet} ##PALETTES##"} {/if} {/if} {if isset($location.Bale)} {if
								$location.Bale==1} {$addInfo="1 ##BALE##"} {else} {$addInfo="{$location.Bale} ##BALES##"} {/if} {/if}
								<tr>
									<td>{if isset($plants.$locationName)}<a href="index.php?page=factories&object={$plants.$locationName.i3dName}">{$locationName}</a>{else}{$locationName}{/if}{if $addInfo} ({$addInfo}){/if}
									</td>
									<td class="text-right">{$location.fillLevel|number_format:0:",":"."}</td>
								</tr>
								{/foreach}
							</tbody>
						</table>
					</td>
				</tr>
				<tr></tr>
				{/if} {/if} {/foreach}
			</tbody>
		</table>
	</div>
	{/for}
</div>
<div class="modal fade" id="optionsDialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">##SETTINGS##</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" action="index.php?page=storage" method="post">
					<div class="form-group">
						<label class="col-sm-5 control-label">##SORT_ORDER##</label>
						<div class="col-sm-7">
							<label class="radio-inline"> <input type="radio" name="sortByName" value="1"{if $options.sortByName}checked{/if}> ##ALPHABETICALLY##
							</label> <label class="radio-inline"> <input type="radio" name="sortByName" value="0"{if !$options.sortByName}checked{/if}> ##FILL_LEVEL##
							</label>
						</div>
					</div>
					<div class="form-group">
						<label for="sortType" class="col-sm-5 control-label">##VEHICLE_LOAD##</label>
						<div class="col-sm-7">
							<label class="radio-inline"> <input type="radio" name="showVehicles" value="1"{if $options.showVehicles}checked{/if}> ##SHOW##
							</label> <label class="radio-inline"> <input type="radio" name="showVehicles" value="0"{if !$options.showVehicles}checked{/if}> ##HIDE##
							</label>
						</div>
					</div>
					<div class="form-group">
						<label for="sortType" class="col-sm-5 control-label">##ONLY_PALETTS##</label>
						<div class="col-sm-7">
							<label class="radio-inline"> <input type="radio" name="onlyPallets" value="1"{if $options.onlyPallets}checked{/if}> ##YES##
							</label> <label class="radio-inline"> <input type="radio" name="onlyPallets" value="0"{if !$options.onlyPallets}checked{/if}> ##NO##
							</label>
						</div>
					</div>
					<div class="form-group">
						<label for="sortType" class="col-sm-5 control-label">##SHOW_ZERO_STOCK##</label>
						<div class="col-sm-7">
							<label class="radio-inline"> <input type="radio" name="hideZero" value="0"{if !$options.hideZero}checked{/if}> ##YES##
							</label> <label class="radio-inline"> <input type="radio" name="hideZero" value="1"{if $options.hideZero}checked{/if}> ##NO##
							</label>
						</div>
					</div>
					<div class="form-group">
						<label for="sortType" class="col-sm-5 control-label">##HIDE_ANIMALS##</label>
						<div class="col-sm-7">
							<label class="radio-inline"> <input type="radio" name="hideAnimalsInStorage" value="1"{if $options.hideAnimalsInStorage}checked{/if}> ##YES##
							</label> <label class="radio-inline"> <input type="radio" name="hideAnimalsInStorage" value="0"{if !$options.hideAnimalsInStorage}checked{/if}> ##NO##
							</label>
						</div>
					</div>
					<div class="form-group">
						<label for="3column" class="col-sm-5 control-label">##LAYOUT##</label>
						<div class="col-sm-7">
							<label class="radio-inline"> <input type="radio" name="3column" value="1"{if $options.3column}checked{/if}> ##3COLUMN##
							</label> <label class="radio-inline"> <input type="radio" name="3column" value="0"{if !$options.3column}checked{/if}> ##4COLUMN##
							</label>
						</div>
					</div>
			
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">##CLOSE##</button>
				<button type="submit" class="btn btn-success">##SAVE##</button>
			</div>
		</div>
		</form>
	</div>
</div>
<div class="modal fade" id="outOfMapAlert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">##INCORRECT_POSITIONS_TITLE##</h4>
			</div>
			<div class="modal-body">
				<p>##INCORRECT_POSITIONS_TEXT##</p>
				<table class="table text-nowrap">
					<thead>
						<tr>
							<th>##PALLET##/##BALE##</th>
							<th>##INCORRECT_POSITION##</th>
							<th>##SUGGESTION_POSITION##</th>
						</tr>
					</thead>
					<tbody>
						{foreach $outOfMap as $item}
						<tr>
							<td>{$item[1]}</td>
							<td class="nowrap">{$item[2]}</td>
							<td class="nowrap">{$item[3]}</td>
						</tr>
						{/foreach}
					</tbody>
				</table>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">##CLOSE##</button>
			</div>
		</div>
	</div>
</div>