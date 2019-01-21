<h3 class="mt-3">##STATISTICS##</h3>
<table class="table table-sm table-striped">
	<thead>
		<tr>
			<th></th> {foreach $farms as $farmId => $farm}
			<th>{$farm.name}</th> {/foreach}
		</tr>
	</thead>
	<tbody>
		{foreach $farm.statistics as $statistic => $value}
		<tr>
			<th>{$statistic}</th>{foreach $farms as $sFrmId => $sFarm}
			<td class="text-right pr-3">{$sFarm.statistics.$statistic|number_format:0:",":"."}</td>{/foreach}
		</tr>
		{/foreach}
	</tbody>
</table>