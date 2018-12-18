<h3 class="mt-3">##FARM##</h3>
<div class="row">
	{foreach $farms as $farm}
	<div class="col-sm-3">
		<h4>{$farm.name}</h4>
		<hr>
		<p class="text-right">{$farm.money|number_format:0:",":"."}</p>
		<hr>
		<strong>##PLAYER##</strong>
		<table class="table table-sm">
			<tbody>
				{foreach $farm.players as $player}
				<tr>
					<td>{$player.name|truncate:10:"...":true}</td>
					<td>{if $player.isFarmManager}##FARMMANAGER##{/if}</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
	{/foreach}
</div>