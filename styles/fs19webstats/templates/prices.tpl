<h3 class="my-3">##PRICES1000##</h3>
{$mode = GetParam('subPage','G','bestPrices')} {if $mode == 'allPrices'}
<!-- Alternative ingame like price overview. Looks not good. -->
<div class="row">
	<div class="col-sm-12">
		<table class="table table-sm table-hover table-bordered table-striped" id="allPrices" width="100%">
			<thead>
				<tr>
					<th>##STOCKS##</th> {foreach $prices as $fillType => $fillTypeData}
					<th class="text-center">{$fillType|truncate:4:""}</th> {/foreach}
				</tr>
			</thead>
			<tbody>
				{foreach $sellingPoints as $location => $i3dName}
				<tr>
					<th nowrap>{$location}</th> {foreach $prices as $fillType => $fillTypeData}{if isset($fillTypeData.locations.$location)}{math equation="round(100 / max * current)" max=$fillTypeData.maxPrice-$fillTypeData.minPrice
					current=$fillTypeData.locations.$location.price-$fillTypeData.locations.$location.minPrice assign="percent"}{/if}
					<td data-order="{if	isset($fillTypeData.locations.$location)}{$fillTypeData.locations.$location.price}{/if}" class="text-right text-nowrap {if isset($fillTypeData.locations.$location)}{if $fillTypeData.locations.$location.greatDemand}text-info{elseif $percent>=60}text-success{elseif $percent<=40}text-danger{/if}{/if}">{if
						isset($fillTypeData.locations.$location)}{$fillTypeData.locations.$location.price|number_format:0:",":"."} {if $fillTypeData.locations.$location.priceTrend == 1}<i class="fas fa-caret-up text-success"></i>{elseif $fillTypeData.locations.$location.priceTrend == -1}<i
						class="fas fa-caret-down text-danger"></i>{else}<i class="fas fa-caret-down" style="visibility: hidden"></i>{/if}{else}&nbsp;{/if}
					</td> {/foreach}
				</tr>
				{/foreach}
			</tbody>
		</table>
		<script>
		var h = window.innerHeight; //Height of the HTML document
		{if $options.hideFooter}
		var c = 230; // Sum of the heights of navbar, footer, headings, etc.
		{else}
		var c = 325; // Sum of the heights of navbar, footer, headings, etc.
		{/if} 
		var th = parseInt((h-c)/h*100) + 'vh'; // Height for table 
		var rw = parseInt((h - c) / 30); // Rows when paging is activated
		$(document).ready(function() {
			$('#allPrices').DataTable( {
				"fixedColumns": true,
		    	"bFilter": false,
		    	"paging": false,		    	
		    	"autoWidth": false,
		    	"info": false,
		    	"scrollX": true,
		    	"scrollY":th,
		        "scrollCollapse": true,
		        "language": {
		    		"url": "./language/{$smarty.session.language}/dataTables.lang"
		    	}
		    } );
		} );
		</script>
		<p class="text-center text-warning">##PRICE_INFO##</p>
	</div>
</div>
{else}
<div class="row">
	<div class="col-sm-12">
		<table class="table table-sm table-hover table-bordered table-striped" id="bestPrices">
			<thead>
				<tr>
					<th class="text-center">##STOCK##</th>
					<th class="text-center">##SELLTRIGGER##</th>
					<th class="text-center">##MIN_PRICE##</th>
					<th class="text-center">##MAX_PRICE##</th>
					<th class="text-center">##BEST_PRICE##</th>
					<th class="text-center">##PERCENT##</th> {if $options['farmId']>0}
					<th class="text-center">##STOCKS##</th>
					<th class="text-center">##PROCEEDS##</th> {/if}
				</tr>
			</thead>
			<tbody>
				{foreach $prices as $fillType => $fillTypeData} {math equation="round(100 / max * current)" max=$fillTypeData.maxPrice-$fillTypeData.minPrice current=$fillTypeData.bestPrice-$fillTypeData.minPrice assign="percent"}
				<tr>
					<td>{$fillType}</td>
					<td>{$fillTypeData.bestLocation}</td>
					<td class="text-right col-1 pr-3">{$fillTypeData.minPrice|number_format:0:",":"."}</td>
					<td class="text-right col-1 pr-3">{$fillTypeData.maxPrice|number_format:0:",":"."}</td>
					<td class="text-right col-1 pr-3 {if $fillTypeData.greatDemand}text-info{elseif $percent>=60}text-success{elseif $percent<=40}text-danger{/if}">{$fillTypeData.bestPrice|number_format:0:",":"."} {if $fillTypeData.priceTrend == 1} <i class="fas fa-caret-up text-success"></i> {elseif
						$fillTypeData.priceTrend == -1} <i class="fas fa-caret-down text-danger"></i> {else} <i class="fas fa-caret-down" style="visibility: hidden"></i> {/if}
					</td>
					<td class="text-center">{$percent|number_format:0:",":"."} %</td> {if $options['farmId']>0}{if isset($commodities.$fillType) && $commodities.$fillType.overall > 0}
					<td class="text-right col-1 pr-3">{$commodities.$fillType.overall|number_format:0:",":"."}</td> {math equation="overall * bestPrice / 1000" overall=$commodities.$fillType.overall bestPrice=$fillTypeData.bestPrice assign="proceeds"}
					<td class="text-right col-1 pr-3">{$proceeds|number_format:0:",":"."}</td> {else}
					<td></td>
					<td></td> {/if}{/if}
				</tr>
				{/foreach}
			</tbody>
		</table>
		<script>
			var h = window.innerHeight; //Height of the HTML document
			{if $options.hideFooter}
			var c = 230; // Sum of the heights of navbar, footer, headings, etc.
			{else}
			var c = 325; // Sum of the heights of navbar, footer, headings, etc.
			{/if} 
			var th = parseInt((h-c)/h*100) + 'vh'; // Height for table 
			var rw = parseInt((h - c) / 30); // Rows when paging is activated
			$(document).ready(function() { 
				var table = $('#bestPrices').DataTable( { 
					//"pageLength": rw, 
					scrollY: th, 
					scrollCollapse: true, 
					paging:	false, 
					stateSave: true, 
					"dom": "<'row'<'col-sm-12'tr>>", 
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
