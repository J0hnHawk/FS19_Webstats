<h3 class="my-3">##ANIMALS##</h3>
{if $stables|@count}
<div class="row">
	<div class="col-3">
		<div id="accordion">
			<div class="list-group">
				{foreach $stables as $stableI3dName => $stable}
				<button type="button" class="list-group-item d-flex justify-content-between align-items-center list-group-item-dark" data-toggle="collapse" data-target="#collapse{$stableI3dName}">
					<strong>{$stable.name}</strong><span class="badge badge-secondary badge-pill">{$stable.numberOfAnimals}</span>
				</button>
				<div id="collapse{$stableI3dName}" class="collapse {if $currentStable == $stableI3dName}show{/if}" data-parent="#accordion">
					{foreach $stable.animals as $animalI3dName => $animal} <a href="index.php?page={$page}&stable={$stableI3dName}&animal={$animalI3dName}" class="list-group-item list-group-item-action"> {$animal.name}{if $animal.isHorse}<br> <small>Tägliches Reiten<span class="float-right">{$animal.ridingTimer|number_format:0:",":"."}
								%</span></small>{else}<span class="float-right">{$animal.count}</span><br> <small>##PRODUCTIVITY##<span class="float-right">{$stable.productivity|number_format:0:",":"."} %</span></small>{/if}
					</a> {foreachelse} <a href="#" class="list-group-item list-group-item-action"> ##NO_ANIMALS_IN_STABLE##<br>&nbsp;
					</a>{/foreach}
				</div>
				{/foreach}
			</div>
		</div>
	</div>
	<div class="col-9">
		<div class="row">
			{if $currentAnimal}
			<div class="col-lg-6">
				<h4>
					{$stables.$currentStable.animals.$currentAnimal.name}<span class="float-right">{if $stables.$currentStable.animals.$currentAnimal.isHorse}€
						{$stables.$currentStable.animals.$currentAnimal.value|number_format:0:",":"."}{else}{$stables.$currentStable.animals.$currentAnimal.count|number_format:0:",":"."}{/if}</span>
				</h4>
				<img src="{#IMAGES#}/animals/{$stables.$currentStable.animals.$currentAnimal.image}.png" class="img-fluid h-50 mx-auto d-block"> {if $stables.$currentStable.forHorses}
				<div class="row">
					<div class="col-4">##FITNESS##</div>
					<div class="col-4">
						<div class="progress">
							{$style='style="width: '|cat:$stables.$currentStable.animals.$currentAnimal.fitnessScale|cat:'%"'}
							<div class="progress-bar" role="progressbar" {$style} aria-valuenow="{$stables.$currentStable.animals.$currentAnimal.fitnessScale}" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
					<div class="col-4">{$stables.$currentStable.animals.$currentAnimal.fitnessScale} %</div>
				</div>
				<div class="row">
					<div class="col-4">##HEALTH##</div>
					<div class="col-4">
						<div class="progress">
							{$style='style="width: '|cat:$stables.$currentStable.animals.$currentAnimal.healthScale|cat:'%"'}
							<div class="progress-bar" role="progressbar" {$style} aria-valuenow="{$stables.$currentStable.animals.$currentAnimal.healthScale}" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
					<div class="col-4">{$stables.$currentStable.animals.$currentAnimal.healthScale} %</div>
				</div>
				<div class="row">
					<div class="col-4">##CLEANLINESS##</div>
					<div class="col-4">
						<div class="progress mt-0">
							{$style='style="width: '|cat:$stables.$currentStable.animals.$currentAnimal.dirtScale|cat:'%"'}
							<div class="progress-bar" role="progressbar" {$style} aria-valuenow="{$stables.$currentStable.animals.$currentAnimal.dirtScale}" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
					<div class="col-4">{$stables.$currentStable.animals.$currentAnimal.dirtScale} %</div>
				</div>
				{else}
				<div class="row">
					<div class="col-6">
						<h5>##PRODUCTIVITY##</h5>
					</div>
					<div class="col-3 text-right">{$stables.$currentStable.productivity|number_format:0:",":"."} %</div>
					<div class="col-3">
						<div class="progress">
							{$style='style="width: '|cat:$stables.$currentStable.productivity|cat:'%"'}
							<div class="progress-bar" role="progressbar" {$style} aria-valuenow="{$stables.$currentStable.productivity}" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
				</div>
				<div class="row mt-1">
					<div class="col-6">##REPRO_RATE##</div>
					<div class="col-6 text-right">{$stables.$currentStable.animals.$currentAnimal.reproRate} h</div>
				</div>
				<div class="row mt-1">
					<div class="col-6">##NEXT_ANIMAL##</div>
					<div class="col-6 text-right">{$stables.$currentStable.animals.$currentAnimal.nextAnimal} h</div>
				</div>
				{foreach $stables.$currentStable.product as $productName => $product}
				<div class="row mt-1">
					<div class="col-6">{$product.name}</div>
					<div class="col-6 text-right">{$product.value|number_format:0:",":"."} {$product.unit}</div>
				</div>
				{/foreach} {/if}
			</div>
			<div class="col-lg-6">
				<h4>##STABLE_INFO##</h4>
				<h5>##STABLE_STATE##</h5>
				{foreach $stables.$currentStable.state as $stateName => $state}
				<div class="row mt-1">
					<div class="col-6">{$state.name}</div>
					<div class="col-3 text-right">{$state.value|number_format:0:",":"."} {$state.unit}</div>
					<div class="col-3">
						<div class="progress">
							{$style='style="width: '|cat:$state.factor|cat:'%"'}
							<div class="progress-bar" role="progressbar" {$style} aria-valuenow="{$state.factor}" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
				</div>
				{/foreach}
				<h5 class="mt-5">##FOOD##</h5>
				{foreach $stables.$currentStable.trough as $foodName => $food}
				<div class="row mt-1">
					<div class="col-6">{$food.name}</div>
					<div class="col-3 text-right">{$food.value|number_format:0:",":"."} {$food.unit}</div>
					<div class="col-3">
						<div class="progress">
							{$style='style="width: '|cat:$food.factor|cat:'%"'}
							<div class="progress-bar" role="progressbar" {$style} aria-valuenow="{$food.factor}" aria-valuemin="0" aria-valuemax="100"></div>
						</div>
					</div>
				</div>
				{/foreach}
			</div>
		</div>
		<div class="row">
			<div class="col">{if isset($stables.$currentStable.product.manure)}##MANURE_HELP##{/if}</div>
		</div>
		{else}
		<div class="jumbotron my-3 py-3 w-100">
			<p class="lead">##NO_ANIMALS_IN_STABLE##</p>
		</div>
		{/if}
	</div>
</div>
{else}
<div class="jumbotron my-3 py-3">
	<p class="lead">##NOSTABLES##</p>
</div>
{/if}
