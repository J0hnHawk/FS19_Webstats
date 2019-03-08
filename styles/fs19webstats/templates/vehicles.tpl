{if $subPage == 'vehicles'}
<h3 class="my-3">##VEH_VEHICLES##</h3>
<div class="row">
	<div class="col-sm-12">
		<table class="table table-sm table-hover display table-bordered table-striped" id="vehicles">
			<thead>
				<tr>
					<!-- <th class="text-center">##VEH_BRAND##</th> -->
					<th class="text-center">##VEH_NAME##</th>
					<th class="text-center">##VEH_CATEGORY##</th>
					<th class="text-center">##VEH_AGE##</th>
					<th class="text-center">##VEH_WEAR##</th>
					<th class="text-center">##VEH_OTIME##</th>
					<th class="text-center">##VEH_RESALE##</th>
					<th class="text-center">##VEH_LPDAY##</th>
					<th class="text-center">##VEH_LPHOUR##</th>
					<th class="text-center">##VEH_LCOST##</th>
				</tr>
			</thead>
			<tbody>
				{foreach $vehicles as $vehicleId => $vehicle}
				<tr>
					<!-- <td>{$vehicle.brand}</td> -->
					<td>{$vehicle.brand} {$vehicle.name}</td>
					<td>{$vehicle.category}</td>
					<td class="text-right pr-3">{$vehicle.age}</td>
					<td class="text-right pr-3">{$vehicle.wear|number_format:0} %</td>
					<td data-order="{$vehicle.operatingTime|number_format:0:" ,":"."}" class="text-right pr-3">{$vehicle.operatingTimeString}</td>
					<td data-order="{if $vehicle.propertyState==1}{$vehicle.resale}{else}0{/if}" class="text-right pr-3">{if $vehicle.propertyState==1}{$vehicle.resale|number_format:0:",":"."}{elseif $vehicle.propertyState==3}Mission{/if}</td>
					<td class="text-right pr-3">{if $vehicle.propertyState==2}{$vehicle.dayLeasingCost|number_format:0:",":"."}{/if}</td>
					<td class="text-right pr-3">{if $vehicle.propertyState==2}{$vehicle.leasingCostPerHour|number_format:0:",":"."}{/if}</td>
					<td class="text-right pr-3">{if $vehicle.propertyState==2}{$vehicle.leasingCost|number_format:0:",":"."}{/if}</td>
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
			scrollX:        true, 				
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
{elseif $subPage == 'buildings'}
<h3 class="my-3">##VEH_BUILDINGS##</h3>
<div class="row">
	<div class="col-sm-12">
		<table class="table table-sm table-hover display table-bordered table-striped" id="buildings">
			<thead>
				<tr>
					<th class="text-center">##VEH_BNAME##</th>
					<th class="text-center">##VEH_AGE##</th>
					<th class="text-center">##VEH_PRICE##</th>
					<th class="text-center">##VEH_RESALE##</th>
				</tr>
			</thead>
			<tbody>
				{foreach $buildings as $buildingId => $building}
				<tr>
					<td>{$building.name}</td>
					<td class="text-right pr-3">{$building.age}</td>
					<td class="text-right pr-3">{$building.price}</td>
					<td data-order="{$building.resale}" class="text-right pr-3">{$building.resale|number_format:0:",":"."}</td>
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
		    var table = $('#buildings').DataTable( {
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
{/if}
