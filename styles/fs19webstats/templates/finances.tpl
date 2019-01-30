{$mode = GetParam('subPage','G','balance')} {if $mode == 'balance'}
<h3 class="my-3">##BS_HEAD_MAIN## "{$farmName}"</h3>
<div class="row">
	<div class="col-md-6 order-1 border-right border-dark mt-3">
		<h5>##BS_HEAD_LEFT##</h5>
		<div class="row border-top pt-2 border-dark">
			<div class="col-9">
				<ul class="list-unstyled">
					<li><span class="h5">##BS_FIXED##</span>
						<ol>
							<li>##BS_LAND##</li>
							<li>##BS_BUILDINGS##</li>
							<li>##BS_VEHICLES##</li>
							<li class="nostyle border-top">##BS_SUM_FIXED##</li>
						</ol></li>
					<li><span class="h5">##BS_ANIMAL##</span>
						<ol>
							<li>##BS_HORSES##</li>
							<li>##BS_COWS##</li>
							<li>##BS_PIGS##</li>
							<li>##BS_SHEEPS##</li>
							<li>##BS_CHICKEN##</li>
							<li class="nostyle border-top text-bold">##BS_SUM_ANIMAL##</li>
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
							<li class="nostyle border-top text-bold">##BS_SUM_CURRENT##</li>
						</ul></li>
				</ul>
			</div>
			<div class="col-3">
				<ul class="list-unstyled">
					<li><span class="h5">&nbsp;</span></li>
					<li class="text-right pr-3">{$assets.A1|number_format:0:",":"."}</li>
					<li class="text-right pr-3">{$assets.A2|number_format:0:",":"."}</li>
					<li class="text-right pr-3">{$assets.A3|number_format:0:",":"."}</li>
					<li class="text-right pr-3 border-top">{$assets.A1+$assets.A2+$assets.A3|number_format:0:",":"."}</li>
					<li><span class="h5">&nbsp;</span></li>
					</li>
					<li class="text-right pr-3">{$assets.B1|number_format:0:",":"."}</li>
					<li class="text-right pr-3">{$assets.B2|number_format:0:",":"."}</li>
					<li class="text-right pr-3">{$assets.B3|number_format:0:",":"."}</li>
					<li class="text-right pr-3">{$assets.B4|number_format:0:",":"."}</li>
					<li class="text-right pr-3">{$assets.B5|number_format:0:",":"."}</li>
					<li class="text-right pr-3 border-top">{$assets.B1+$assets.B2+$assets.B3+$assets.B4+$assets.B5|number_format:0:",":"."}</li>
					<li><span class="h5">&nbsp;</span></li>
					</li>
					<li>&nbsp;</li>
					<li class="text-right pr-3">{$assets.CI1|number_format:0:",":"."}</li>
					<!-- <li class="text-right pr-3">{if isset($assets.CI2)}{$assets.CI2|number_format:0:",":"."}{/if}</li> -->
					<li class="text-right pr-3">{$assets.CI3|number_format:0:",":"."}</li>
					<li class="text-right pr-3">{$assets.CII|number_format:0:",":"."}</li>
					<li class="text-right pr-3">{$assets.CIII|number_format:0:",":"."}</li>
					<li class="text-right pr-3 border-top">{$assets.CI1+$assets.CI3+$assets.CII+$assets.CIII|number_format:0:",":"."}</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-md-6 order-3 order-md-2 mt-3">
		<h5 class="text-right">##BS_HEAD_RIGHT##</h5>
		<div class="row border-top pt-2 border-dark">
			<div class="col-9">
				<ul class="list-unstyled">
					<li><span class="h5">##BS_SHAREHOLDERS_EQUITY##</span></li>
					<ol>
						<li>##BS_INITIAL##</li>
						<li>##BS_PROFIT##</li>
						<li class="nostyle border-top text-bold">##BS_SUM_SHAREHOLDERS_EQUITY##</li>
					</ol>
					</li>
					<li><span class="h5">##BS_LIABILITIES##</span></li>
					<ol>
						<li>##BS_BANKDEBT##</li>
						<li>##BS_BANKOPCREDIT##</li>
						<li class="nostyle border-top text-bold">##BS_SUM_LIABILITIES##</li>
					</ol>
					</li>
				</ul>
			</div>
			<div class="col-3">
				<ul class="list-unstyled">
					<li><span class="h5">&nbsp;</span></li>
					</li>
					<li class="text-right pr-3">{$liabilities.A1|number_format:0:",":"."}</li>
					<li class="text-right pr-3">{$liabilities.A2|number_format:0:",":"."}</li>
					<li class="text-right pr-3 border-top">{$liabilities.A1+$liabilities.A2|number_format:0:",":"."}</li>
					<li><span class="h5">&nbsp;</span></li>
					</li>
					<li class="text-right pr-3">{$liabilities.B1|number_format:0:",":"."}</li>
					<li class="text-right pr-3">{$liabilities.B2|number_format:0:",":"."}</li>
					<li class="text-right pr-3 border-top">{$liabilities.B1+$liabilities.B2|number_format:0:",":"."}</li>
				</ul>
			</div>
		</div>
	</div>
	<!-- <div class="col-md-6 d-none d-lg-block border-right border-top border-bottom border-dark"> -->
	<div class="col-md-6 order-2 order-md-3 border-right border-top border-bottom border-dark">
		<div class="row">
			<div class="col-9">
				<p class="h5 pt-1">##BS_TOTAL_LEFT##</p>
			</div>
			<div class="col-3">
				<p class="h5 text-right pr-3 pt-1">{$balanceSheetSum|number_format:0:",":"."}</p>
			</div>
		</div>
	</div>
	<div class="col-md-6 order-4 border-top border-bottom border-dark">
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
{elseif $mode == 'ratios'}
<h3 class="my-3">##BUSINESS_RATIOS##</h3>
<div class="row mt-3">
	<div class="col-6">
		<h5>
			##BWL_ADG## <a href="#BWLADG" data-toggle="collapse" class="small">Details</a><span class="float-right">{$ratios.adg|number_format:0:",":"."} %</span>
		</h5>
		<div class="collapse" id="BWLADG">
			<p class="text-justify">##BWL_ADG_P1##</p>
			<p>
				<var>##BWL_ADG##</var>
				=
				<var>##BWL_EK##</var>
				x 100 :
				<var>##BWL_AV##</var>
			</p>
		</div>
		<p class="text-justify">##BWL_ADG_P2##</p>
	</div>
	<div class="col-6">
		<h5>
			##BWL_EKQ## <a href="#BWLEKQ" data-toggle="collapse" class="small">Details</a><span class="float-right">{$ratios.ekq|number_format:0:",":"."} %</span>
		</h5>
		<div class="collapse" id="BWLEKQ">
			<p class="text-justify">##BWL_EKQ_P1##</p>
			<p>
				<var>##BWL_EKQ##</var>
				=
				<var>##BWL_EK##</var>
				x 100 :
				<var>##BWL_GK##</var>
			</p>
		</div>
		<p class="text-justify">##BWL_EKQ_P2##</p>
	</div>
	<div class="col-6">
		<h5>
			##BWL_EKR## <a href="#BWLEKR" data-toggle="collapse" class="small">Details</a><span class="float-right">{$ratios.ekr|number_format:0:",":"."} %</span>
		</h5>
		<div class="collapse" id="BWLEKR">
			<p class="text-justify">##BWL_EKR_P1##</p>
			<p>
				<var>##BWL_EKR##</var>
				=
				<var>##BWL_G##</var>
				x 100 :
				<var>##BWL_EK##</var>
			</p>
		</div>
		<p class="text-justify">##BWL_EKR_P2##</p>
	</div>
	<div class="col-6">
		<h5>
			##BWL_FKQ## <a href="#BWLFKQ" data-toggle="collapse" class="small">Details</a><span class="float-right">{$ratios.fkq|number_format:0:",":"."} %</span>
		</h5>
		<div class="collapse" id="BWLFKQ">
			<p class="text-justify">##BWL_FKQ_P1##</p>
			<p>
				<var>##BWL_FKQ##</var>
				=
				<var>##BWL_FK##</var>
				x 100 :
				<var>##BWL_GK##</var>
			</p>
		</div>
		<p class="text-justify">##BWL_FKQ_P2##</p>
	</div>
	<div class="col-6">
		<h5>
			##BWL_FKD## <a href="#BWLFK" data-toggle="collapse" class="small">Details</a><span class="float-right">{$ratios.fkd|number_format:0:",":"."} %</span>
		</h5>
		<div class="collapse" id="BWLFK">
			<p class="text-justify">##BWL_FKD_P1##</p>
			<p>
				<var>##BWL_FKD##</var>
				= (
				<var>##BWL_AAV##</var>
				+
				<var>##BWL_TV##</var>
				+
				<var>##BWL_UV##</var>
				) x 100 :
				<var>##BWL_FK##</var>
			</p>
		</div>
		<p class="text-justify">##BWL_FKD_P2##</p>
	</div>
	<div class="col-6">
		<h5>
			Liquidität <a href="#BWLL" data-toggle="collapse" class="small">Details</a><span class="float-right">{$ratios.l3g|number_format:0:",":"."} %</span>
		</h5>
		<div class="collapse" id="BWLl">
			<p class="text-justify">##BWL_L_P1##</p>
			<p>
				<var>##BWL_L##</var>
				=
				<var>##BWL_UV##</var>
				x 100 :
				<var>##BWL_FK##</var>
			</p>
		</div>
		<p class="text-justify">##BWL_L_P2##</p>
	</div>
	<div class="col-6">
		<h5>
			##BWL_GKR## <a href="#BWLGKR" data-toggle="collapse" class="small">Details</a><span class="float-right">{$ratios.gkr|number_format:0:",":"."} %</span>
		</h5>
		<div class="collapse" id="BWLGKR">
			<p class="text-justify">##BWL_GKR_P1##</p>
			<p>
				<var>##BWL_GKR##</var>
				= (
				<var>##BWL_G##</var>
				+
				<var>##BWL_KZ##</var>
				) x 100 :
				<var>##BWL_EK##</var>
			</p>
		</div>
		<p class="text-justify">##BWL_GKR_P2##</p>
	</div>
	<div class="col-12">
		<p class="text-center">
			##BWL_COPYRIGHT## <a href="https://debitoor.de/lexikon">debitor</a>
		</p>
	</div>
</div>
{else}
<h3 class="my-3">##FINANCES##</h3>
<div class="row">
	<div class="col-sm-12">
		<table id="finances" class="table table-sm table-hover table-bordered table-striped" width="100%">
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
