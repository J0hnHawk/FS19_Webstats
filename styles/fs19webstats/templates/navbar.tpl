<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark justify-content-center py-0">
	<a class="navbar-brand" href="#">{$map.Short} {$map.Version} Web Stats</a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse justify-content-between align-items-center" id="navbarNav">
		<ul class="navbar-nav mx-auto text-center">
		{foreach $navItems as $link => $navItem} 
			{if $navItem.showInNav}
				{if $navItem.active} 
					{$class="nav-item active"}
				{else}
					{$class="nav-item"}
				{/if}
				{if $navItem.hasSubmenu}
					<li class="{$class|cat:' dropdown'}">
						<a class="nav-link dropdown-toggle py-0" href="index.php?page={$link}" id="navbarDropdown" data-toggle="dropdown"><img src="{#IMAGES#}/{$link}.png" class="img-fluid"></a><!--  {$navItem.text} -->
						<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						{foreach $navItem.submenu as $subLink => $subItem}
							{if $subItem.showInNav}
								<a class="dropdown-item" href="index.php?page={$link}&subPage={$subLink}">{$subItem.text}</a>
							{/if}
						{/foreach}
						</div>
					</li>
				{else}
					<li class="{$class}">
						<a class="nav-link py-0" href="index.php?page={$link}"><img src="{#IMAGES#}/{$link}.png" class="img-fluid"></a><!-- {$navItem.text} -->
					</li>
				{/if}
			{/if}
		{/foreach}
		</ul>
		<span class="navbar-text  bg-secondary text-white px-3 text-right font-weight-bold text-nowrap">##DAY## {$currentDay}, {$dayTime}{if $money !== false} | {$money|number_format:0:",":"."}{/if}</span>
	</div>
</nav>
