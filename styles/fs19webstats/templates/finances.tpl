{$mode = GetParam('subPage','G','balance')} {if $mode == 'balance'}
<h3 class="my-3">##BS_HEAD_MAIN## "{$farmName}"</h3>
<div class="row">
	<div class="col-md-6 border-right border-dark">
		<h5>##BS_HEAD_LEFT##</h5>
		<div class="row border-top pt-2 border-dark">
			<div class="col-9">
				<ul class="list-unstyled">
					<li><span class="h5">##BS_FIXED##</span>
						<ol>
							<li>##BS_LAND##</li>
							<li>##BS_BUILDINGS##</li>
							<li>##BS_VEHICLES##</li>
						</ol></li>
					<li><span class="h5">##BS_ANIMAL##</span>
						<ol>
							<li>##BS_HORSES##</li>
							<li>##BS_COWS##</li>
							<li>##BS_PIGS##</li>
							<li>##BS_SHEEPS##</li>
							<li>##BS_CHICKEN##</li>
						</ol></li>
					<li><span class="h5">##BS_CURRENT##</span>
						<ul class="list-unstyled ml-4">
							<li>##BS_INVENTORY##
								<ol>
									<li>##BS_SUPPLIES##</li>
									<!-- <li>Feldinventar</li> -->
									<li>##BS_STORAGE##</li>
								</ol>
							</li>
							<li>##BS_ACCRECEIV##</li>
							<li>##BS_CASH##</li>
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
					<!-- <li class="text-right pr-3">{if isset($assets.CI2)}{$assets.CI2|number_format:0:",":"."}{else}0{/if}</li> -->
					<li class="text-right pr-3">{if isset($assets.CI3)}{$assets.CI3|number_format:0:",":"."}{else}0{/if}</li>
					<li class="text-right pr-3">{if isset($assets.CII)}{$assets.CII|number_format:0:",":"."}{else}0{/if}</li>
					<li class="text-right pr-3">{if isset($assets.CIII)}{$assets.CIII|number_format:0:",":"."}{else}0{/if}</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<h5 class="text-right">##BS_HEAD_RIGHT##</h5>
		<div class="row border-top pt-2 border-dark">
			<div class="col-9">
				<ul class="list-unstyled">
					<li><span class="h5">##BS_SHAREHOLDERS_EQUITY##</span></li>
					<ol>
						<li>##BS_INITIAL##</li>
						<li>##BS_PROFIT##</li>
					</ol>
					</li>
					<li><span class="h5">##BS_LIABILITIES##</span></li>
					<ol>
						<li>##BS_BANKDEBT##</li>
						<li>##BS_BANKOPCREDIT##</li>
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
				<p class="h5 pt-1">##BS_TOTAL_LEFT##</p>
			</div>
			<div class="col-3">
				<p class="h5 text-right pr-3 pt-1">{$balanceSheetSum|number_format:0:",":"."}</p>
			</div>
		</div>
	</div>
	<div class="col-md-6 border-top border-bottom border-dark">
		<div class="row">
			<div class="col-9">
				<p class="h5 pt-1">##BS_TOTAL_RIGHT##</p>
			</div>
			<div class="col-3">
				<p class="h5 text-right pr-3 pt-1">{$balanceSheetSum|number_format:0:",":"."}</p>
			</div>
		</div>
	</div>
</div>
{assign "eigenkapital" $liabilities.A1 + $liabilities.A2} {assign "fremdkapital" $liabilities.B1+$liabilities.B2} {assign "anlagevermoegen" $assets.A1+$assets.A2+$assets.A3+1} {assign "kurzfristigesFremdkapital" $liabilities.B1+$liabilities.B2+1}{assign "vorraete" $assets.CI1+$assets.CI3}
<!-- Eigenkapitalquote -->
{math equation="round(100 * eigenkapital / gesamtkapital)" eigenkapital=$eigenkapital gesamtkapital=$balanceSheetSum assign="ekq"}
<!-- Eigenkapitalrentabilität -->
{math equation="round(100 * gewinn / eigenkapital)" eigenkapital=$eigenkapital gewinn=$liabilities.A2 assign="ekr"}
<!-- Anlagendeckungsgrad -->
{math equation="round(100 * eigenkapital / anlagevermoegen)" eigenkapital=$eigenkapital anlagevermoegen=$anlagevermoegen assign="adg"}
<!-- Fremdkapitalquote -->
{math equation="round(100 * fremdkapital / gesamtkapital)" fremdkapital=$fremdkapital gesamtkapital=$balanceSheetSum assign="fkq"}
<!-- Liquidität 2. Grades -->
{math equation="round(100 * (fluessigeMittel + kurzfristigeForderungen + vorraete) / kurzfristigesFremdkapital)" fluessigeMittel=$assets.CIII kurzfristigeForderungen=$assets.CII kurzfristigesFremdkapital=$kurzfristigesFremdkapital vorraete=$vorraete assign="l3g"}
<!-- Gesamtkapitalrentabilität -->
{math equation="round(100 * (gewinn + fremdkapitalzinsen) / eigenkapital)" eigenkapital=$eigenkapital fremdkapitalzinsen=0 gewinn=$liabilities.A2 assign="gkr"}
<div class="row mt-3">
	<div class="col-6">
		<h5>
			Anlagendeckungsgrad<span class="float-right">{$adg|number_format:0:",":"."} %</span>
		</h5>
		<p class="text-justify">Ein hoher Anlagendeckungsgrad bedeutet, dass große Teile des Anlagevermögens über Eigenkapital und nicht per Kredit „auf Pump“ finanziert werden.</p>
	</div>
	<div class="col-6">
		<h5>
			Eigenkapitalquote<span class="float-right">{$ekq|number_format:0:",":"."} %</span>
		</h5>
		<p class="text-justify">Eine hohe Eigenkapitalquote gilt als positiv: Der Hof finanziert sich vorwiegend aus eigener Kraft und muss nicht auf Fremdkapital zurückgreifen.</p>
	</div>
	<div class="col-6">
		<h5>
			Eigenkapitalrentabilität<span class="float-right">{$ekr|number_format:0:",":"."} %</span>
		</h5>
		<p class="text-justify">Liegt die Eigenkapitalrentabilität über dem aktuellen Zinssatz, hat sich die Investition in den eigenen Hof gelohnt.</p>
	</div>
	<div class="col-6">
		<h5>
			Fremdkapitalquote<span class="float-right">{$fkq|number_format:0:",":"."} %</span>
		</h5>
		<p class="text-justify">Je niedriger der Anteil an Fremdkapital am Gesamtkapital ist, desto finanziell unabhängiger ist der Hog. Er finanziert sich dann in erster Linie aus eigenen Kapitalreserven (Eigenkapital).</p>
	</div>
	<div class="col-6">
		<h5>
			Liquidität 3. Grades<span class="float-right">{$l3g|number_format:0:",":"."} %</span>
		</h5>
		<p class="text-justify">Je höher die Liquidität, umso besser ist die Zahlungsfähigkeit (Solvenz) eines Hofes. Die Liquidität gilt daher auch als Gradmesser für eine drohende Insolvenz.</p>
	</div>
	<div class="col-6">
		<h5>
			Gesamtkapitalrentabilität<span class="float-right">{$gkr|number_format:0:",":"."} %</span>
		</h5>
		<p class="text-justify">Die Gesamtkapitalrendite gibt Auskunft darüber, wie hoch die Erträge aus investiertem Gesamtkapital ausfallen.</p>
	</div>
	<div class="col-12">
		<p class="text-center">
			Formeln und Beschreibungen von <a href="https://debitoor.de/lexikon">debitor</a>
		</p>
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
					<td class="text-right {if $money-$loan < 0}text-danger{else}text-success{/if}" colspan="2"><strong>(##LOAN##: {{$loan|number_format:0:",":"."}}) {($money-$loan)|number_format:0:",":"."}</strong></td>
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
