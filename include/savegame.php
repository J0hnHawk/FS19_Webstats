<?php
/**
 *
 * This file is part of the "FS19 Web Stats" package.
 * Copyright (C) 2017-2019 John Hawk <john.hawk@gmx.net>
 *
 * "FS19 Web Stats" is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * "FS19 Web Stats" is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
if (! defined ( 'IN_FS19WS' )) {
	exit ();
}

// Daten laden
switch ($config ['configType']) {
	case 'api' :
		/* FS17 Code:
		$serverAddress = "http://$dSrvIp:$dSrvPort/feed/%scode=$dSrvCode";
		$stats = getServerStatsSimpleXML ( sprintf ( $serverAddress, 'dedicated-server-stats.xml?' ) );
		$careerVehicles = getServerStatsSimpleXML ( sprintf ( $serverAddress, 'dedicated-server-savegame.html?file=vehicles&' ) );
		$careerSavegame = getServerStatsSimpleXML ( sprintf ( $serverAddress, 'dedicated-server-savegame.html?file=careerSavegame&' ) );
		$careerEconomy = getServerStatsSimpleXML ( sprintf ( $serverAddress, 'dedicated-server-savegame.html?file=economy&' ) );
		if (! $careerVehicles || ! $careerVehicles || ! $careerEconomy) {
			$stats = false;
		}
		*/
		break;
	case 'ftp' :
		break;
	case 'local' :
		$stats = 1;
		if (file_exists ( $config['path'] . 'careerSavegame.xml' )) {
			$careerSavegame = simplexml_load_file ( $config['path'] . 'careerSavegame.xml' );
		} else {
			$stats = false;
		}
		if (file_exists ( $config['path'] . 'economy.xml' )) {
			$careerEconomy = simplexml_load_file ( $config['path'] . 'economy.xml' );
		} else {
			$stats = false;
		}
		if (file_exists ( $config['path'] . 'environment.xml' )) {
			$careerEnvironment = simplexml_load_file ( $config['path'] . 'environment.xml' );
		} else {
			$stats = false;
		}
		if (file_exists ( $config['path'] . 'farmland.xml' )) {
			$careerFarmland = simplexml_load_file ( $config['path'] . 'farmland.xml' );
		} else {
			$stats = false;
		}
		if (file_exists ( $config['path'] . 'farms.xml' )) {
			$careerFarms = simplexml_load_file ( $config['path'] . 'farms.xml' );
		} else {
			$stats = false;
		}
		if (file_exists ( $config['path'] . 'items.xml' )) {
			$careerItems = simplexml_load_file ( $config['path'] . 'items.xml' );
		} else {
			$stats = false;
		}		
		if (file_exists ( $config['path'] . 'vehicles.xml' )) {
			$careerVehicles = simplexml_load_file ( $config['path'] . 'vehicles.xml' );
		} else {
			$stats = false;
		}
		break;
}
if ($stats) {
	$serverOnline = true;
	echo("OK");
} else {
	$serverOnline = false;
	echo("NÖ");
	return;
}

$commodities = $outOfMap = $positions = $plants = $placeables = $sellingPoints = $prices = $greatDemands = array ();

// Stand der Daten ermitteln (Ingame-Zeitpunkt der Speicherung)
$careerEnvironment = simplexml_load_file ( $config ['path'] . 'environment.xml' );
$currentDay = $careerEnvironment->currentDay;
$dayTime = $careerEnvironment->dayTime * 60;
$dayTime = gmdate ( "H:i", $dayTime );
$smarty->assign ( 'currentDay', intval ( $currentDay ) );
$smarty->assign ( 'dayTime', $dayTime );
$demandIsRunning = false;

foreach ( $careerEconomy->greatDemands->greatDemand as $greatDemand ) {
	$stationName = strval ( $greatDemand ['stationName'] );
	$fillTypeName = strval ( $greatDemand ['fillTypeName'] );
	$demandMultiplier = floatval ( $greatDemand ['demandMultiplier'] );
	$isRunning = get_bool ( $greatDemand ['isRunning'] );
	$demandIsRunning = $demandIsRunning || $isRunning;
	$l_fillType = translate ( $fillTypeName );
	if (isset ( $greatDemands [$l_fillType] )) {
		$greatDemands [$l_fillType] ['locations'] += array (
				$stationName => array (
						'demandMultiplier' => $demandMultiplier,
						'isRunning' => $isRunning
				)
		);
	} else {
		$greatDemands [$l_fillType] = array (
				'i3dName' => $fillTypeName,
				'locations' => array (
						$stationName => array (
								'demandMultiplier' => $demandMultiplier,
								'isRunning' => $isRunning
						)
				)
		);
	}
}

// Platzierbare Objekte suchen, mapconfig ergänzen
foreach ( $careerVehicles->item as $item ) {
	$filename = cleanFileName ( $item ['filename'] );
	if (! isset ( $placeableObjects [$filename] ['locationType'] )) {
		continue;
	}
	if (isset ( $placeables [$filename] )) {
		$placeables [$filename] ++;
	} else {
		$placeables [$filename] = 1;
	}
	$placeableKey = $filename . str_replace ( array (
			" ",
			".",
			"-" 
	), "", $item ['position'] );
	$mapconfig [$placeableKey] = $placeableObjects [$filename];
	$mapconfig [$placeableKey] ['position'] = strval ( $item ['position'] );
	if (isset ( $mapconfig [$placeableKey] ['output'] )) {
		list ( $px, $py, $pz ) = explode ( ' ', $mapconfig [$placeableKey] ['position'] );
		foreach ( $mapconfig [$placeableKey] ['output'] as $trigger => $triggerData ) {
			if (isset ( $mapconfig [$placeableKey] ['output'] [$trigger] ['palettArea'] )) {
				list ( $x1, $z1, $x2, $z2 ) = explode ( ' ', $mapconfig [$placeableKey] ['output'] [$trigger] ['palettArea'] );
				$x1 = $x1 + $px;
				$x2 = $x2 + $px;
				$z1 = $z1 + $pz;
				$z2 = $z2 + $pz;
				$mapconfig [$placeableKey] ['output'] [$trigger] ['palettArea'] = "$x1 $z1 $x2 $z2";
			}
		}
	}
	$lang [$placeableKey] = $placeablesLang [$filename] . " #" . $placeables [$filename];
}

// Paletten, Ballen
foreach ( $careerVehicles->item as $item ) {
	$className = strval ( $item ['className'] );
	$fillType = false;
	$location = getLocation ( $item ['position'] );
	if (stristr ( $className, 'pallet' ) !== false || $className == 'Bale') {
		if (isset ( $item ['i3dFilename'] )) {
			$fillType = cleanFileName ( $item ['i3dFilename'] );
		} else {
			$fillType = cleanFileName ( $item ['filename'] );
		}
		if ($fillType) {
			$fillLevel = intval ( $item ['fillLevel'] );
			addCommodity ( $fillType, $fillLevel, $location, $className );
			if ($location == 'outOfMap') {
				$commodities [translate ( $fillType )] ['outOfMap'] = true;
				// für Modal Dialog mit Edit-Vorschlag, Platzierung beim Palettenlager
				$outOfMap [] = array (
						$className,
						$fillType,
						strval ( $item ['position'] ),
						'-870 100 ' . (- 560 + sizeof ( $outOfMap ) * 2) 
				);
			} else {
				$positions [$className] [translate ( $fillType )] [] = array (
						'name' => $className,
						'position' => explode ( ' ', $item ['position'] ) 
				);
			}
		}
	}
	$filename = cleanFileName ( $item ['filename'] );
	if (! isset ( $placeableObjects [$filename] ['locationType'] )) {
		continue;
	}
	$position = strval ( $item ['position'] );
	$placeableKey = $filename . str_replace ( array (
			" ",
			".",
			"-" 
	), "", $position );
	readMapObject ( $item, $placeableKey, $plants, $mapconfig );
}

// Fahrzeuge aus $careerVehicles
foreach ( $careerVehicles->vehicle as $vehicle ) {
	$location = cleanFileName ( $vehicle ['filename'] );
	$position = $vehicle->component1 ['position'];
	if (isset ( $vehicle ['fillTypes'] )) {
		$fillTypes = explode ( ' ', $vehicle ['fillTypes'] );
		$fillLevels = explode ( ' ', $vehicle ['fillLevels'] );
		foreach ( $fillTypes as $key => $fillType ) {
			$fillType = strval ( $fillType );
			$fillLevel = intval ( $fillLevels [$key] );
			if ($fillType == 'unknown') {
				continue;
			} else {
				addCommodity ( $fillType, $fillLevel, $location, 'isVehicle' );
				$positions ['vehicle'] [translate ( $fillType )] [] = array (
						'name' => $location,
						'position' => explode ( ' ', $position ) 
				);
			}
		}
	}
	if (isset ( $vehicle ['numBales'] )) {
		$cargos = array (
				'rbale' => 'Bale',
				'wool' => 'FillablePallet',
				'wood2' => 'FillablePallet' 
		);
		foreach ( $cargos as $elements => $className ) {
			if (isset ( $vehicle->$elements )) {
				foreach ( $vehicle->$elements as $element ) {
					$fillType = cleanFileName ( $element ['filename'] );
					$fillLevel = intval ( $element ['fillLevel'] );
					addCommodity ( $fillType, $fillLevel, $location, $className );
					$positions ['vehicle'] [translate ( $fillType )] [] = array (
							'name' => $location,
							'position' => explode ( ' ', $position ) 
					);
				}
			}
		}
	}
}

// Analysierung von CreateLoadedObjects
foreach ( $careerVehicles->onCreateLoadedObject as $object ) {
	$location = strval ( $object ['saveId'] );
	// Lager, Fabriken usw. analysieren
	if (! isset ( $mapconfig [$location] ['locationType'] )) {
		// Objekte, die nicht in der Kartenkonfiguration aufgeführt sind, werden ignoriert
		continue;
	} else {
		// zunächst schauen, ob es sich um eine Verkaufsstelle handelt
		if (isset ( $mapconfig [$location] ['isSellingPoint'] ) && $mapconfig [$location] ['isSellingPoint']) {
			$l_location = translate ( $location );
			$sellingPoints [$l_location] = $location;
			if ($mapconfig [$location] ['locationType'] == 'bga') {
				// Preise der BGA in weiterem Kind-Element
				$foreach = $object->tipTrigger->stats;
			} else {
				$foreach = $object->stats;
			}
			foreach ( $foreach as $triggerStats ) {
				$fillType = strval ( $triggerStats ['fillType'] );
				$isInPlateau = get_bool ( $triggerStats ['isInPlateau'] );
				$l_fillType = translate ( $fillType );
				if (! isset ( $prices [$l_fillType] )) {
					$prices [$l_fillType] = array (
							'i3dName' => $fillType,
							'bestPrice' => 0,
							'maxPrice' => 0,
							'minPrice' => 65535,
							'priceTrend' => 0,
							'bestLocation' => '',
							'greatDemand' => false,
							'locations' => array () 
					);
				}
				extract ( getPrices ( $triggerStats->curveBaseCurve, $triggerStats->curve1 ) );
				if ($isInPlateau) {
					$priceTrend = 0;
				}
				$greatDemand = 1;
				if (isset ( $greatDemands [$l_fillType] ['locations'] [$l_location] )) {
					if ($greatDemands [$l_fillType] ['locations'] [$l_location] ['isRunning']) {
						$greatDemand = $greatDemands [$l_fillType] ['locations'] [$l_location] ['demandMultiplier'];
					}
				}
				$prices [$l_fillType] ['locations'] [$l_location] = array (
						'i3dName' => $location,
						'price' => $currentPrice * $greatDemand,
						'greatDemand' => ($greatDemand > 1) ? true : false,
						'maxPrice' => $maxPrice,
						'minPrice' => $minPrice,
						'priceTrend' => $priceTrend 
				);
				if ($currentPrice > $prices [$l_fillType] ['bestPrice']) {
					$prices [$l_fillType] ['bestPrice'] = $currentPrice * $greatDemand;
					$prices [$l_fillType] ['bestLocation'] = $l_location;
					$prices [$l_fillType] ['greatDemand'] = ($greatDemand > 1) ? true : false;
					$prices [$l_fillType] ['priceTrend'] = $priceTrend;
				}
				if ($maxPrice > $prices [$l_fillType] ['maxPrice']) {
					$prices [$l_fillType] ['maxPrice'] = $maxPrice;
				}
				if ($minPrice < $prices [$l_fillType] ['minPrice']) {
					$prices [$l_fillType] ['minPrice'] = $minPrice;
				}
			}
		}
		// weitere Analyse
		readMapObject ( $object, $location, $plants, $mapconfig );
	}
}
function readMapObject($object, $location, &$plants, &$mapconfig) {
	global $commodities, $animalPallets;
	switch ($mapconfig [$location] ['locationType']) {
		case 'storage' :
			// Farmsilo und andere Lager
			foreach ( $object->node as $node ) {
				$fillType = strval ( $node ['fillType'] );
				$fillLevel = intval ( $node ['fillLevel'] );
				addCommodity ( $fillType, $fillLevel, $location );
			}
			break;
		case 'fuelStation' :
			// Tankstellen
			$fillType = 'fuel';
			$fillLevel = intval ( $object ['fillLevel'] );
			addCommodity ( $fillType, $fillLevel, $location );
			break;
		case 'bunker' :
			// Fahrsilos
			$state = intval ( $object ['state'] );
			$fillLevel = intval ( $object ['fillLevel'] );
			addCommodity ( 'chaff', ($state < 2) ? $fillLevel : 0, $location );
			addCommodity ( 'silage', ($state < 2) ? 0 : $fillLevel, $location );
			break;
		case 'bga' :
			$fillType = 'digestate';
			if ($mapconfig [$location] ['output'] [$fillType] ['showInStorage']) {
				$fillLevel = intval ( $object ['digestateSiloFillLevel'] );
				addCommodity ( $fillType, $fillLevel, $location );
			}
			if ($mapconfig [$location] ['showInProduction']) {
				$plant = translate ( $location );
				$prodPerHour = $mapconfig [$location] ['ProdPerHour'];
				$plants [$plant] = array (
						'i3dName' => $location,
						'position' => $mapconfig [$location] ['position'],
						'state' => 0,
						'input' => array (),
						'output' => array () 
				);
				foreach ( $mapconfig [$location] ['input'] as $fillType => $fillTypeData ) {
					$fillLevel = intval ( $object [$fillTypeData ['fillTypes']] );
					$l_fillType = translate ( $fillType );
					$fillMax = $mapconfig [$location] ['input'] [$fillType] ['capacity'];
					$state = 0;
					if (is_numeric ( $fillMax )) {
						$state = getState ( $fillLevel, $fillMax );
						if ($state > $plants [$plant] ['state']) {
							$plants [$plant] ['state'] = $state;
						}
					} elseif ($fillLevel == 0) {
						$state = 2;
					}
					addCommodity ( $fillType, 0, NULL, NULL, false );
					$plants [$plant] ['input'] [$l_fillType] = addFillType ( $fillType, $fillLevel, $fillMax, $prodPerHour, $mapconfig [$location] ['input'] [$fillType] ['factor'], $state );
				}
				foreach ( $mapconfig [$location] ['output'] as $fillType => $fillTypeData ) {
					$fillLevel = intval ( $object [$fillTypeData ['fillTypes']] );
					$l_fillType = translate ( $fillType );
					$fillMax = $mapconfig [$location] ['output'] [$fillType] ['capacity'];
					$state = 0;
					if (is_numeric ( $fillMax )) {
						$state = getState ( $fillMax - $fillLevel, $fillMax );
					}
					$plants [$plant] ['output'] [$l_fillType] = addFillType ( $fillType, $fillLevel, $fillMax, $prodPerHour, $mapconfig [$location] ['output'] [$fillType] ['factor'], $state );
				}
			}
			break;
		case 'animal' :
			// Viehhaltung
			$numAnimals = intval ( $object ['numAnimals0'] );
			addCommodity ( substr ( $location, 8, 99 ), $numAnimals, $location, 'animal' );
			$cleanlinessFactor = floatval ( $object ['cleanlinessFactor'] );
			$ProdPerHour = $numAnimals / 24;
			$plant = translate ( $location );
			$plants [$plant] = array (
					'i3dName' => $location,
					'position' => $mapconfig [$location] ['position'],
					'state' => 0,
					'nameAnimals' => substr ( $location, 8, 99 ),
					'numAnimals' => $numAnimals,
					'cleanlinessFactor' => intval ( $cleanlinessFactor * 100 ) 
			);
			// Futtertröge zusammenrechnen
			$fillTypes = array ();
			foreach ( $object->tipTriggerFillLevel as $tipTrigger ) {
				foreach ( $mapconfig [$location] ['input'] as $combineFillType => $fillTypeData ) {
					if (! isset ( $fillTypes [$combineFillType] ))
						$fillTypes [$combineFillType] = 0;
					if (strpos ( $fillTypeData ['fillTypes'], strval ( $tipTrigger ['fillType'] ) ) !== false) {
						$fillTypes [$combineFillType] += intval ( $tipTrigger ['fillLevel'] );
					}
				}
			}
			// Kapazitäten & Produktivität errechnen
			$tipTriggers = '';
			foreach ( $fillTypes as $combineFillType => $fillLevel ) {
				if ($fillLevel != 0) {
					$tipTriggers .= $combineFillType;
				}
				$l_fillType = translate ( $combineFillType );
				$trough_factor = $mapconfig [$location] ['input'] [$combineFillType] ['trough_factor'];
				$usage_factor = $mapconfig [$location] ['input'] [$combineFillType] ['consumption_factor'];
				$fillMax = getMaxForage ( $trough_factor, $numAnimals );
				if ($fillMax < $fillLevel) {
					$fillMax = $fillLevel;
				}
				$state = getState ( $fillLevel, $fillMax );
				if ($state > $plants [$plant] ['state']) {
					$plants [$plant] ['state'] = $state;
				}
				$plants [$plant] ['input'] [$l_fillType] = addFillType ( $combineFillType, $fillLevel, $fillMax, $ProdPerHour, $usage_factor, $state );
			}
			if ($numAnimals == 0) {
				$productivity = 0;
			} else {
				$productivity = getAnimalProductivity ( $location, $tipTriggers ) * (($cleanlinessFactor < 0.1) ? 0.9 : 1);
			}
			$plants [$plant] ['productivity'] = $productivity;
			if ($numAnimals > 1 && $productivity != 0) {
				$reproRate = intval ( $mapconfig [$location] ['reproRate'] / $numAnimals * 3600 * 100 / $productivity );
			} else {
				$reproRate = 0;
			}
			$plants [$plant] ['reproRate'] = gmdate ( "H:i", $reproRate );
			$plants [$plant] ['nextAnimal'] = gmdate ( "H:i", $reproRate * (1 - floatval ( $object ['newAnimalPercentage'] )) );
			// Produktion
			$output = array ();
			switch ($location) {
				case 'Animals_sheep' :
					break;
				case 'Animals_cow' :
					// Milch
					$output ['milk'] = intval ( $object->fillLevelMilk ['fillLevel'] );
				case 'Animals_pig' :
					// Gülle & Mist
					$output ['manure'] = intval ( $object ['manureFillLevel'] );
					$output ['liquidManure'] = intval ( $object ['liquidManureFillLevel'] );
					foreach ( $output as $fillType => $fillLevel ) {
						addCommodity ( $fillType, $fillLevel, $location );
						if (isset ( $mapconfig [$location] ['output'] [$fillType] ['capacity'] )) {
							$fillMax = $mapconfig [$location] ['output'] [$fillType] ['capacity'];
							$state = getState ( $fillMax - $fillLevel, $fillMax );
						} else {
							$fillMax = '&infin;';
							$state = 0;
						}
						$factor = $mapconfig [$location] ['output'] [$fillType] ['production_factor'];
						$plants [$plant] ['output'] [translate ( $fillType )] = addFillType ( $fillType, $fillLevel, $fillMax, $ProdPerHour, $factor, $state );
					}
					break;
			}
			// Prüfung auf Herstellung von Paletten
			foreach ( $mapconfig [$location] ['output'] as $fillType => $fillTypeData ) {
				if (isset ( $fillTypeData ['palettPlaces'] )) {
					if (empty ( $animalPallets ) || ! is_array ( $animalPallets )) {
						$animalPallets = array ();
					}
					$animalPallets [] = $fillType;
					$l_fillType = translate ( $fillType );
					$factor = $mapconfig [$location] ['output'] [$fillType] ['production_factor'];
					$fillLevel = isset ( $commodities [$l_fillType] ['locations'] [$plant] ['fillLevel'] ) ? $commodities [$l_fillType] ['locations'] [$plant] ['fillLevel'] : 0;
					$fillMax = $mapconfig [$location] ['output'] [$fillType] ['palettPlaces'] * $mapconfig [$location] ['output'] [$fillType] ['capacity'];
					$state = getState ( $fillMax - $fillLevel, $fillMax );
					$plants [$plant] ['output'] [$l_fillType] = addFillType ( $fillType, $fillLevel, $fillMax, $ProdPerHour, $factor, $state );
					addCommodity ( $fillType, $fillLevel, $location );
				}
			}
			break;
		case 'extraFilePHP' :
			// Lade extra spezialScript nach
			global $map;
			$scriptFile = sprintf ( './config/%s/%s', $map ['Path'], $mapconfig [$location] ['scriptFile'] );
			if (file_exists ( $scriptFile )) {
				include ($scriptFile);
			}
			break;
		case 'FabrikScript' :
			// Factoryscript Lager in Commodities aufnehmen
			foreach ( $object->Rohstoff as $in ) {
				$fillType = strval ( $in ['Name'] );
				$fillLevel = getPositiveInt ( $in ['Lvl'] );
				if ($mapconfig [$location] ['input'] [$fillType] ['showInStorage']) {
					addCommodity ( $fillType, $fillLevel, $location );
				}
			}
			foreach ( $object->Produkt as $out ) {
				$fillType = strval ( $out ['Name'] );
				$fillLevel = getPositiveInt ( $out ['Lvl'] );
				if ($mapconfig [$location] ['output'] [$fillType] ['showInStorage']) {
					addCommodity ( $fillType, $fillLevel, $location );
				} else {
					addCommodity ( $fillType, 0, $location );
				}
			}
			// Fabriken für Produktionsübersicht
			if ($mapconfig [$location] ['showInProduction']) {
				$plant = translate ( $location );
				$plantstate = 0;
				$prodPerHour = $mapconfig [$location] ['ProdPerHour'];
				$plants [$plant] = array (
						'i3dName' => $location,
						'position' => $mapconfig [$location] ['position'],
						'state' => 0,
						'input' => array (),
						'output' => array () 
				);
				foreach ( $object->Rohstoff as $rohstoff ) {
					$fillType = strval ( $rohstoff ['Name'] );
					if (isset ( $mapconfig [$location] ['input'] [$fillType] ['showInProduction'] ) && ! $mapconfig [$location] ['input'] [$fillType] ['showInProduction']) {
						continue;
					}
					$l_fillType = translate ( $fillType );
					$fillLevel = getPositiveInt ( $rohstoff ['Lvl'] );
					$factor = $mapconfig [$location] ['input'] [$fillType] ['factor'];
					$fillMax = $mapconfig [$location] ['input'] [$fillType] ['capacity'];
					$state = getState ( $fillLevel, $fillMax );
					if ($state > $plants [$plant] ['state']) {
						$plants [$plant] ['state'] = $state;
					}
					$plants [$plant] ['input'] [$l_fillType] = addFillType ( $fillType, $fillLevel, $fillMax, $prodPerHour, $factor, $state );
				}
				foreach ( $object->Produkt as $product ) {
					$fillType = strval ( $product ['Name'] );
					if (isset ( $mapconfig [$location] ['output'] [$fillType] ['showInProduction'] ) && ! $mapconfig [$location] ['output'] [$fillType] ['showInProduction']) {
						continue;
					}
					$l_fillType = translate ( $fillType );
					$outputConfig = $mapconfig [$location] ['output'] [$fillType];
					$factor = $outputConfig ['factor'];
					if ($outputConfig ['showInStorage']) {
						$fillLevel = getPositiveInt ( $product ['Lvl'] );
						$fillMax = $outputConfig ['capacity'];
					} else {
						$fillLevel = isset ( $commodities [$l_fillType] ['locations'] [$plant] ['fillLevel'] ) ? $commodities [$l_fillType] ['locations'] [$plant] ['fillLevel'] : 0;
						$capacity = $outputConfig ['capacity'];
						$fillMax = ((isset ( $outputConfig ['palettPlaces'] )) ? $outputConfig ['palettPlaces'] : 1) * $capacity;
					}
					$state = getState ( $fillMax - $fillLevel, $fillMax );
					$plants [$plant] ['output'] [$l_fillType] = addFillType ( $fillType, $fillLevel, $fillMax, $prodPerHour, $factor, $state );
				}
			}
			break;
	}
}

// "Kombi-Rohstoffe ermitteln (Abfall, Brennstoffe, usw.)
foreach ( $mapconfig as $plantName => $plant ) {
	if (isset ( $plant ['input'] )) {
		foreach ( $plant ['input'] as $combineFillType => $data ) {
			$l_combineFillType = translate ( $combineFillType );
			$fillTypes = explode ( ' ', $data ['fillTypes'] );
			if (! isset ( $commodities [$l_combineFillType] )) { // && sizeof ( $fillTypes ) > 1
				foreach ( $fillTypes as $fillType ) {
					$l_fillType = translate ( $fillType );
					if (! isset ( $commodities [$l_fillType] )) {
						addCommodity ( $fillType, 0, NULL, NULL, false );
					}
					$fillLevel = $commodities [$l_fillType] ['overall'];
					addCommodity ( $combineFillType, $fillLevel, NULL, NULL, true );
				}
			}
		}
	}
}
function getPrice($amplitude0, $amplitude1, $period0, $period1, $time0, $time1, $nominalAmplitude1) {
	$sin1 = $amplitude0 * sin ( (2 * pi () / $period0) * $time0 );
	$sin2 = $amplitude1 * sin ( (2 * pi () / $period1) * $time1 ) + $nominalAmplitude1 * 10;
	return ($sin1 + $sin2) * 1000;
}
function getPrices($curve0, $curve1) {
	$curve0 = objects2float ( $curve0 );
	$curve1 = objects2float ( $curve1 );
	$currentPrice = getPrice ( $curve0 ['amplitude'], $curve1 ['amplitude'], $curve0 ['period'], $curve1 ['period'], $curve0 ['time'], $curve1 ['time'], $curve1 ['nominalAmplitude'] );
	$offset = 1000;
	$nextPrice = getPrice ( $curve0 ['amplitude'], $curve1 ['amplitude'], $curve0 ['period'], $curve1 ['period'], $curve0 ['time'] + $offset, $curve1 ['time'] + $offset, $curve1 ['nominalAmplitude'] );
	$maxPrice = 0;
	$minPrice = 65535;
	$timeDifference = $curve1 ['time'] - $curve0 ['time'];
	$possibleAmplitude0 = $curve0 ['nominalAmplitude'] + $curve0 ['nominalAmplitudeVariation'];
	$possibleAmplitude1 = $curve1 ['nominalAmplitude'] + $curve1 ['nominalAmplitudeVariation'];
	$possiblePeriod0 = $curve0 ['nominalPeriod'] + $curve0 ['nominalPeriodVariation'];
	$possiblePeriod1 = $curve1 ['nominalPeriod'] + $curve1 ['nominalPeriodVariation'];
	$maxSin = 0;
	$minSin = 65535;
	for($time = 0; $time < $possiblePeriod0; $time += $possiblePeriod0 / 800) {
		$sin = $possibleAmplitude0 * sin ( (2 * pi () / $possiblePeriod0) * $time );
		if ($sin > $maxSin)
			$maxSin = $sin;
		if ($sin < $minSin)
			$minSin = $sin;
	}
	$max0 = $maxSin;
	$min0 = $minSin;
	$maxSin = 0;
	$minSin = 65535;
	for($time = 0; $time < $possiblePeriod1; $time += $possiblePeriod1 / 800) {
		$sin = $possibleAmplitude1 * sin ( (2 * pi () / $possiblePeriod1) * $time ) + $curve1 ['nominalAmplitude'] * 10;
		if ($sin > $maxSin)
			$maxSin = $sin;
		if ($sin < $minSin)
			$minSin = $sin;
	}
	$max1 = $maxSin;
	$min1 = $minSin;
	$maxPrice = ($max0 + $max1) * 1000;
	$minPrice = ($min0 + $min1) * 1000;
	return array (
			'currentPrice' => $currentPrice,
			'minPrice' => $minPrice,
			'maxPrice' => $maxPrice,
			'priceTrend' => $nextPrice > $currentPrice ? 1 : ($currentPrice > $nextPrice ? - 1 : 0) 
	);
}
function objects2float($objectArray) {
	$floatArray = array ();
	foreach ( $objectArray->attributes () as $key => $value ) {
		$floatArray [$key] = floatval ( $value );
	}
	return $floatArray;
}
