<h3 class="mt-3">##MISSIONS##</h3>
<div class="row">
	<div class="col-sm-12">
		<table class="table table-sm table-hover table-bordered table-striped" id="missions">
			<thead>
				<tr>
					<th class="text-center">##TYPE##</th>
					<th class="text-center">##REWARD##</th>
					<th class="text-center">##STATUS##</th>
					<th class="text-center">##FARM##</th>
					<th class="text-center">##SUCCESS##</th>
					<th class="text-center">##FIELD##</th>
				</tr>
			</thead>
			<tbody>
				{foreach $missions as $mission}
				<tr>
					<td>{$mission.type}</td>
					<td class="text-right">{$mission.reward|number_format:0:",":"."}</td>
					<td class="text-center">{if $mission.status}##ACTIVE##{/if}</td>
					<td>{if isset($mission.farmId)}{$mission.farmId}{/if}</td>
					<td>{$mission.success}</td>
					<td>{if isset($mission.field)}{$mission.field}{/if}</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
		<script>
		var rows = parseInt(($( window ).height() - 370) / 30)
		$(document).ready(function() {
		    var table = $('#missions').DataTable( {
		    	"pageLength": 20,
		    	stateSave: true,
		    	"dom":	"<'row'<'col-sm-6'><'col-sm-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-5'i><'col-sm-7'p>>",		
		    	"language": {
		    		"decimal": ",",
		            "thousands": ".",
		            "url": "./language/{$smarty.session.language}/dataTables.lang"
		    	}
		    } );
		} );
		</script>
	</div>
</div>