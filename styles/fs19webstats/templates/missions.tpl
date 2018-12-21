<h3 class="mt-3">##MISSIONS##</h3>
<div class="row">
	<div class="col-sm-12">
		<table class="table table-sm table-hover table-bordered table-striped" id="missions">
			<thead>
				<tr>
					<th class="text-center">##FIELD##</th>
					<th class="text-center">##TYPE##</th>
					<th class="text-center">##REWARD##</th>
					<th class="text-center">##FARM##</th>
					<th class="text-center">##STATUS##</th>
				</tr>
			</thead>
			<tbody>
				{foreach $missions as $mission}
				<tr>
					<td>{if isset($mission.field)}{$mission.field}{/if}</td>
					<td>{$mission.type}</td>
					<td class="text-right">{$mission.reward|number_format:0:",":"."}</td>
					<td>{if isset($mission.farmId)}{$farms[$mission.farmId].name}{/if}</td>
					<td>{if $mission.success}abgeschlossen{elseif $mission.status}##ACTIVE##{/if}</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
		<script>
		var h = window.innerHeight; 			//Height of the HTML document
		var c = 335; 							// Sum of the heights of navbar, footer, headings, etc.  
		var th = parseInt((h-c)/h*100) + 'vh';	// Height for table
		var rw = parseInt((h - c) / 30);		// Rows when paging is activated
		$(document).ready(function() {
		    var table = $('#missions').DataTable( {
		    	//"pageLength": rw,
		    	scrollY:        th,
        		scrollCollapse: true,
       			paging:         false,
		    	stateSave:		true,
		    	"dom":	"<'row'<'col-sm-12'tr>><'row mt-3'<'col-sm-6'><'col-sm-6'f>>", // cut from end: <'row'<'col-sm-5'i><'col-sm-7'p>>		
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