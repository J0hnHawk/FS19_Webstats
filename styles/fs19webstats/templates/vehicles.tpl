<h3 class="mt-3">##H3VEHICLES##</h3>
<div class="row">
	<div class="col-sm-12">
		<table class="table table-sm table-hover display table-bordered table-striped" id="vehicles">
			<thead>
				<tr>
					<th class="text-center">##VNAME##</th>
					<th class="text-center">##VAGE##</th>
					<th class="text-center">##VWEAR##</th>
					<th class="text-center">##VOTIME##</th>
					<th class="text-center">##VRESALE##</th>
					<th class="text-center">##VLPDAY##</th>
					<th class="text-center">##VLPHOUR##</th>
					<th class="text-center">##VLCOST##</th>
				</tr>
			</thead>
			<tbody>
				{foreach $vehicles as $vehicleId => $vehicle}
				<tr>
					<td>{$vehicle.name}</td>
					<td class="text-right pr-3">{$vehicle.age}</td>
					<td class="text-right pr-3">{$vehicle.wear|number_format:0} %</td>
					<td data-order="{$vehicle.operatingTime|number_format:0:" ,":"."}" class="text-right pr-3">{$vehicle.operatingTimeString}</td>
					<td class="text-right pr-3">{if $vehicle.propertyState==1}{$vehicle.resale|number_format:0:",":"."}{elseif $vehicle.propertyState==3}Mission{/if}</td>
					<td>{if $vehicle.propertyState==2}{$vehicle.dayLeasingCost|number_format:0:",":"."}{/if}</td>
					<td>{if $vehicle.propertyState==2}{$vehicle.leasingCostPerHour|number_format:0:",":"."}{/if}</td>
					<td>{if $vehicle.propertyState==2}{$vehicle.leasingCost|number_format:0:",":"."}{/if}</td>
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