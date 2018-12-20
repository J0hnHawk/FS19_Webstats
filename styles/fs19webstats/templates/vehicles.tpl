<h3 class="mt-3">##H3VEHICLES##</h3>
<div class="row">
	<div class="col-sm-12">
		<table class="table table-sm table-hover table-bordered table-striped" id="vehicles">
			<thead>
				<tr>
					<th class="text-center">##VNAME##</th>
					<th class="text-center">##VAGE##</th>
					<th class="text-center">##VWEAR##</th>
					<th class="text-center">##VPRICE##</th>
					<th class="text-center">##VPSTATE##</th>
					<th class="text-center">##VOTIME##</th>
				</tr>
			</thead>
			<tbody>
				{foreach $vehicles as $vehicleId => $vehicle}
				<tr>
					<td>{$vehicle.name}</td>
					<td>{$vehicle.age}</td>
					<td>{$vehicle.wear|number_format:0} %</td>
					<td>{$vehicle.price|number_format:0:",":"."}</td>
					<td>{if $vehicle.propertyState==2}gemietet{elseif $vehicle.propertyState==3}Mission{/if}</td>
					<td data-order="{$vehicle.opTimeTS|number_format:0:",":"."}">{$vehicle.operatingTime}</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
		<script>
		var rows = parseInt(($( window ).height() - 370) / 30)
		$(document).ready(function() {
		    var table = $('#vehicles').DataTable( {
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