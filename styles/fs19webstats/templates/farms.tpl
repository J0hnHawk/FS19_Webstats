<h3 class="my-3">##FARMS##</h3>
<div class="row">
	<div class="col">
		<div class="table-responsive">
			<table class="table table-borderless">
				<thead>
					<tr class="d-flex">
						{foreach $farms as $farmId => $farm}
						<th class="col-3"><h4>{$farm.name}</h4></th> {/foreach}
					</tr>
				</thead>
				<tbody>
					<tr class="d-flex">
						{foreach $farms as $farmId => $farm}
						<td class="col-3"><strong>##FINANCES##: <span class="float-right">{$farm.money|number_format:0:",":"."}</span></strong></td> {/foreach}
					</tr>
					<tr class="d-flex">
						{foreach $farms as $farmId => $farm}
						<td class="col-3"><strong>##PLAYER##</strong><br> {foreach $farm.players as $player}
							<p class="my-1">
								{$player.name|truncate:10:"...":true}{if $player.isFarmManager}<span class="float-right">##FARMMANAGER##</span>{/if}
							</p>{/foreach}</td> {/foreach}
					</tr>
					<tr class="d-flex">
						{foreach $farms as $farmId => $farm}
						<td class="col-3">{if ($farm.contractFrom|@count) + ($farm.contractWith|@count)>0} <strong>##CONTRACTS##</strong>
							<ul class="list-unstyled mt-1">
								{if $farm.contractFrom|@count>0}
								<li>##WORKSFOR##
									<ul>
										{foreach $farm.contractFrom as $farmId2 => $bool}
										<li>{$farms.$farmId2.name}</li>{/foreach}
									</ul>
								</li> {/if}{if $farm.contractWith|@count>0}
								<li>##HASWORKSFOR##
									<ul>
										{foreach $farm.contractWith as $farmId2 => $bool}
										<li>{$farms.$farmId2.name}</li>{/foreach}
									</ul>
								</li>{/if}
							</ul> {/if}
						</td> {/foreach}
					</tr>
					<tr class="d-flex">
						{foreach $farms as $farmId => $farm}
						<td class="col-3">{if $selectedFarm == $farmId} <a href="index.php?page={$page}&leave_farm={$farmId}" class="btn btn-danger btn-sm btn-block" role="button">##LEAVE_FARM##</a> {else} <a href="index.php?page={$page}&join_farm={$farmId}" class="btn btn-primary btn-sm btn-block" role="button">##JOIN_FARM##</a>{/if}
						</td> {/foreach}
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
