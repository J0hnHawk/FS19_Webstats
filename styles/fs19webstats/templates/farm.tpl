<h3 class="mt-3">##FARM##</h3>
<div class="row">
	{foreach $farms as $farmId => $farm}
	<div class="col-sm-3">
		<h4>{$farm.name}</h4>
		<hr>
		<p class="text-right">{$farm.money|number_format:0:",":"."}</p>
		<hr>
		<strong>##PLAYER##</strong>
		<table class="table table-sm table-borderless">
			<tbody>
				{foreach $farm.players as $player}
				<tr>
					<td>{$player.name|truncate:10:"...":true}</td>
					<td class="text-right">{if $player.isFarmManager}##FARMMANAGER##{/if}</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
		{if ($farm.contractFrom|@count) + ($farm.contractWith|@count)>0}
		<hr>
		<strong>##CONTRACTS##</strong>
		<ul class="list-unstyled">
			{if $farm.contractFrom|@count>0}
			<li>##WORKSFOR##
				<ul>
					{foreach $farm.contractFrom as $farmId2 => $bool}
					<li>{$farms.$farmId2.name}</li>{/foreach}
				</ul>
			</li> {/if}{if $farm.contractWith|@count>0}
			<li>##HASWORKSFOR##
				<ul>
					{foreach $farm.contractWith as $farmId2 => $bool}
					<li>{$farms.$farmId2.name}</li>{/foreach}
				</ul>
			</li>{/if}
		</ul>
		{/if}
		{if $selectedFarm == $farmId}
		<a href="index.php?page={$page}&leave_farm={$farmId}" class="btn btn-danger btn-sm btn-block" role="button">##LEAVE_FARM##</a>
		{else}
		<a href="index.php?page={$page}&join_farm={$farmId}" class="btn btn-primary btn-sm btn-block" role="button">##JOIN_FARM##</a>{/if}
	</div>
	{/foreach}
</div>