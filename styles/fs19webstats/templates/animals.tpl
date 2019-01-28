<h3 class="my-3">##ANIMALS##</h3>
<!-- .anyClass {  height:150px;  overflow-y: scroll; }
height: calc(100vh - 100px)
 -->
<div class="row">
	<div class="col-3">
		<div class="list-group">
			{foreach $stables as $stableName => $stable}
			<button type="button" class="list-group-item list-group-item-dark">
				<strong>{$stableName}</strong>
			</button>
			{foreach $stable.animals as $animalName => $animal}
			<a href="index.php?page={$page}&stable={$stable.i3dName}&animal={$animal.i3dName}" class="list-group-item list-group-item-action">{$animalName}<span class="float-right">{$animal.count}</span><br>
			<small>Produktivität<span class="float-right">{$stable.productivity|number_format:0:",":"."} %</span></small></a>
			{/foreach}
			{/foreach}
		</div>
	</div>
	<div class="col-9">
		<div class="row">
			<div class="col-6">
				<h4>
					Schafe (Schwarz & Weiß)<span class="float-right">95</span>
				</h4>
			</div>
			<div class="col-6">
				<h4>Stallinformationen</h4>
				<h5>Zustand</h5>
				<p>Sauberkeit</p>
				<p>Wasser</p>
			</div>
		</div>
	</div>
</div>