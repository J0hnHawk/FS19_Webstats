<h3 class="my-3">##STATISTICS##</h3>
<div class="table-responsive">
	<table class="table table-sm table-striped table-borderless">
		<thead>
			<tr>
				<th></th> {foreach $farms as $farmId => $farm}
				<th>{$farm.name}</th> {/foreach}
			</tr>
		</thead>
		<tbody>
			{foreach $farm.statistics as $statistic => $value}
			<tr>
				<th>{$value.item}{if $value.category == 'litres'} [##LITRES##]{elseif $value.category == 'hectares'} [##HA##]{/if}</th> {foreach $farms as $sFrmId => $sFarm}
				<td class="text-right pr-3">{if $sFarm.statistics.$statistic.category == 'count'} {$sFarm.statistics.$statistic.value|number_format:0:",":"."} {elseif $sFarm.statistics.$statistic.category == 'litres'} {$sFarm.statistics.$statistic.value|number_format:2:",":"."} {elseif
					$sFarm.statistics.$statistic.category == 'hectares'} {$sFarm.statistics.$statistic.value|number_format:2:",":"."} {elseif $sFarm.statistics.$statistic.category == 'time'} {$sFarm.statistics.$statistic.value} {/if}</td>{/foreach}
			</tr>
			{/foreach}
		</tbody>
	</table>
</div>
