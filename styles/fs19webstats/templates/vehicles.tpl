<h3 class="mt-3">##H3VEHICLES##</h3>
<div class="row">
	<div class="col-sm-12">
		<table class="table table-sm table-hover display table-bordered table-striped" id="vehicles">
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
					<td class="text-right pr-3">{$vehicle.age}</td>
					<td class="text-right pr-3">{$vehicle.wear|number_format:0} %</td>
					<td class="text-right pr-3">{$vehicle.price|number_format:0:",":"."}</td>
					<td>{if $vehicle.propertyState==2}gemietet{elseif $vehicle.propertyState==3}Mission{/if}</td>
					<td data-order="{$vehicle.opTimeTS|number_format:0:",":"."}" class="text-right pr-3">{$vehicle.operatingTime}</td>
				</tr>
				{/foreach}
			</tbody>
		</table>
		<script>
		var h = window.innerHeight; 			//Height of the HTML document
		var c = 285; 							// Sum of the heights of navbar, footer, headings, etc.  
		var th = parseInt((h-c)/h*100) + 'vh';	// Height for table
		var rw = parseInt((h - c) / 30);		// Rows when paging is activated
		$(document).ready(function() {
		    var table = $('#vehicles').DataTable( {
		    	//"pageLength": rw,
		    	scrollY:        th,
        		scrollCollapse: true,
       			paging:         false,
		    	stateSave:		true,
		    	"dom":	"<'row'<'col-sm-12'tr>>", // cut from beginn: <'row'<'col-sm-6'><'col-sm-6'f>> cut from end: <'row'<'col-sm-5'i><'col-sm-7'p>>		
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