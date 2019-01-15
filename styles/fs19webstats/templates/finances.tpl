<div class="page-header">
	<h3>
		##FINANCES##<small> (##SAVETIME##: ##DAY## {$currentDay}, {$dayTime})</small>
	</h3>
</div>
<div class="row">
	<div class="col-sm-12">
		<table id="finances" class="table table-hover table-bordered table-striped" width="100%">
			<thead>
				<tr>
					<th>##INCOME_EXPENDITURE##</th>
					<th class="text-right">##{$weekdays[($currentDay-4)%7]}##</th>
					<th class="text-right">##{$weekdays[($currentDay-3)%7]}##</th>
					<th class="text-right">##{$weekdays[($currentDay-2)%7]}##</th>
					<th class="text-right">##{$weekdays[($currentDay-1)%7]}##</th>
					<th class="text-right">##TODAY##</th>
				</tr>
			</thead>
			<tbody>
				{foreach $financeElements as $element => $category}
				<tr>
					<td>##{$element|strtoupper}##</td> {for $day = 4 to 0 step -1}
					<td class="text-right" style="width: 13%">{$financeHistory.$day.$element|number_format:0:",":"."}</td> {/for}
				</tr>
				{/foreach}
			</tbody>
			<tfoot>
				<tr>
					<th>##TOTAL##</th> {for $day = 4 to 0 step -1}
					<th class="text-right" style="width: 13%">{$financeHistory.$day.total|number_format:0:",":"."}</th> {/for}
				</tr>
				<tr>
					<td colspan="4"><strong>##BALANCE1##</strong></td>
					<td class="text-right" colspan="2"><strong>{$money|number_format:0:",":"."}</strong></td>
				</tr>
				<tr>
					<td colspan="4"><strong>##BALANCE2##</strong></td>
					<td class="text-right {if $money-$loan < 0}text-danger{else}text-success{/if}" colspan="2"><strong>(##LOAN##: {{$loan|number_format:0:",":"."}})
						{($money-$loan)|number_format:0:",":"."}</strong></td>
				</tr>
			</tfoot>
		</table>
		<script>
		var rows = parseInt(($( window ).height() - 250) / 36)
		$(document).ready(function() {
		    var table = $('#finances').DataTable( {
		        scrollY:        "55vh",
		        "ordering": false,
		        "dom":"<'row'<'col-sm-12'tr>>",
		        scrollX:        false,
		        scrollCollapse: true,
		        paging:         false,
		        fixedColumns:   true
		    } );
		    var table = $('#bestPrices').DataTable( {
		    	"pageLength": rows,
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