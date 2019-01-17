{$mode = GetParam('subPage','G','balance')} {if $mode == 'balance'}
<h3 class="mt-3">##BALANCESHEET## ##TO## ##DAY## {$currentDay} ##FORFARM## "{$farmName}"</h3>
<div class="row">
	<div class="col-md-6 border-right border-dark">
		<h4>##ASSETS##</h4>
		<div class="row border-top pt-2 border-dark">
			<div class="col-9">
				<ul class="list-unstyled">
					<li><span class="h5">A. Anlagevermögen</span>
						<ol>
							<li>Grundstücke</li>
							<li>Gebäude</li>
							<li>Fahrzeuge</li>
						</ol></li>
					<li><span class="h5">B. Tiervermögen</span>
						<ol>
							<li>Hühner</li>
							<li>Schafe</li>
							<li>Schweine</li>
							<li>Rinder</li>
							<li>Pferde</li>
						</ol></li>
					<li><span class="h5">C. Umlaufvermögen</span>
						<ul class="list-unstyled ml-4">
							<li>I. Vorräte
								<ol>
									<li>Roh- Hilfs- und Betriebsstoffe</li>
									<li>Feldinventar</li>
									<li>Fertige Erzeugnisse</li>
								</ol>
							</li>
							<li>II. Forderungen</li>
							<li>III. Bankguthaben</li>
						</ul></li>
				</ul>
			</div>
			<div class="col-3">
				<ul class="list-unstyled">
					<li><span class="h5">&nbsp;</span></li>
					<li class="text-right pr-3">{if isset($assets.A1)}{$assets.A1|number_format:0:",":"."}{else}0{/if}</li>
					<li class="text-right pr-3">{if isset($assets.A2)}{$assets.A2|number_format:0:",":"."}{else}0{/if}</li>
					<li class="text-right pr-3">{if isset($assets.A3)}{$assets.A3|number_format:0:",":"."}{else}0{/if}</li>
					<li><span class="h5">&nbsp;</span></li>
					</li>
					<li class="text-right pr-3">{if isset($assets.B1)}{$assets.B1|number_format:0:",":"."}{else}0{/if}</li>
					<li class="text-right pr-3">{if isset($assets.B2)}{$assets.B2|number_format:0:",":"."}{else}0{/if}</li>
					<li class="text-right pr-3">{if isset($assets.B3)}{$assets.B3|number_format:0:",":"."}{else}0{/if}</li>
					<li class="text-right pr-3">{if isset($assets.B4)}{$assets.B4|number_format:0:",":"."}{else}0{/if}</li>
					<li class="text-right pr-3">{if isset($assets.B5)}{$assets.B5|number_format:0:",":"."}{else}0{/if}</li>
					<li><span class="h5">&nbsp;</span></li>
					</li>
					<li>&nbsp;</li>
					<li class="text-right pr-3">{if isset($assets.CI1)}{$assets.CI1|number_format:0:",":"."}{else}0{/if}</li>
					<li class="text-right pr-3">{if isset($assets.CI2)}{$assets.CI2|number_format:0:",":"."}{else}0{/if}</li>
					<li class="text-right pr-3">{if isset($assets.CI3)}{$assets.CI3|number_format:0:",":"."}{else}0{/if}</li>
					<li class="text-right pr-3">{if isset($assets.CII)}{$assets.CII|number_format:0:",":"."}{else}0{/if}</li>
					<li class="text-right pr-3">{if isset($assets.CIII)}{$assets.CIII|number_format:0:",":"."}{else}0{/if}</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<h4 class="text-right">##LIABILITIES##</h4>
		<div class="row border-top pt-2 border-dark">
			<div class="col-9">
				<ul class="list-unstyled">
					<li><span class="h5">A. Eigenkapital</span></li>
					<ol>
						<li>Anfangskapital</li>
						<li>Gewinn</li>
					</ol>
					</li>
					<li><span class="h5">B. Verbindlichkeiten</span></li>
					<ol>
						<li>Kredit</li>
						<li>Kontokorrent</li>
					</ol>
					</li>
				</ul>
			</div>
			<div class="col-3">
				<ul class="list-unstyled">
					<li><span class="h5">&nbsp;</span></li>
					</li>
					<li class="text-right pr-3">{if isset($liabilities.A1)}{$liabilities.A1|number_format:0:",":"."}{else}0{/if}</li>
					<li class="text-right pr-3">{if isset($liabilities.A2)}{$liabilities.A2|number_format:0:",":"."}{else}0{/if}</li>
					<li><span class="h5">&nbsp;</span></li>
					</li>
					<li class="text-right pr-3">{if isset($liabilities.B1)}{$liabilities.B1|number_format:0:",":"."}{else}0{/if}</li>
					<li class="text-right pr-3">{if isset($liabilities.B2)}{$liabilities.B2|number_format:0:",":"."}{else}0{/if}</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-md-6 d-none d-lg-block border-right border-top border-bottom border-dark">
		<div class="row">
			<div class="col-9">
				<p class="h5">Bilanzsumme</p>
			</div>
			<div class="col-3">
				<p class="h5 text-right pr-3">{$balanceSheetSum|number_format:0:",":"."}</p>
			</div>
		</div>
	</div>
	<div class="col-md-6 border-top border-bottom border-dark">
		<div class="row">
			<div class="col-9">
				<p class="h5">Bilanzsumme</p>
			</div>
			<div class="col-3">
				<p class="h5 text-right pr-3">{$balanceSheetSum|number_format:0:",":"."}</p>
			</div>
		</div>
	</div>
</div>
{else}
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
{/if}
