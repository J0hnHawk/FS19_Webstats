<?php
/**
 *
 * This file is part of the "FS19 Web Stats" package.
 * Copyright (C) 2017-2018 John Hawk <john.hawk@gmx.net>
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
$xmlFiles = array (
		'economy.xml',
		'environment.xml',
		'items.xml' 
);
foreach ( $xmlFiles as $xmlFile ) {
	$fp = fopen ( "./cache/$xmlFile", "w" );
	$url = "ftp://" . $config ['user'] . ":" . $config ['pass'] . "@" . $config ['server'] . ":" . $config ['port'] . $config ['path'] . $xmlFile;
	$handle = curl_init ();
	curl_setopt ( $handle, CURLOPT_URL, $url );
	curl_setopt ( $handle, CURLOPT_RETURNTRANSFER, 0 );
	curl_setopt ( $handle, CURLOPT_UPLOAD, 0 );
	curl_setopt ( $handle, CURLOPT_FILE, $fp );
	$result = curl_exec ( $handle );
	if ($result) {
	} else {
		echo "Übertragung fehlgeschlagen";
	}
	curl_close ( $handle );
}
// $careerEconomy = simplexml_load_file ( $config ['path'] . 'economy.xml' );
// $careerEnvironment = simplexml_load_file ( $config ['path'] . 'environment.xml' );
// $careerItems = simplexml_load_file ( $config ['path'] . 'items.xml' );
$careerEconomy = simplexml_load_file ( './cache/economy.xml' );
$careerEnvironment = simplexml_load_file ( './cache/environment.xml' );
$careerItems = simplexml_load_file ( './cache/items.xml' );

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
foreach ( $careerItems->item as $item ) {
	$className = strval ( $item ['className'] );
	if ($className == 'SellingStationPlaceable') {
		$location = cleanFileName ( $item ['filename'] );
		// Lager, Fabriken usw. analysieren
		if (! isset ( $mapconfig [$location] ['locationType'] )) {
			echo("$location<br>");
			// Objekte, die nicht in der Kartenkonfiguration aufgeführt sind, werden ignoriert
			continue;
		} else {
			if (isset ( $mapconfig [$location] ['isSellingPoint'] ) && $mapconfig [$location] ['isSellingPoint']) {
				$l_location = translate ( $location );
				$sellingPoints [$l_location] = $location;
				if ($mapconfig [$location] ['locationType'] == 'bga') {
					/*
					 * BGA not yet analyzed
					 * $foreach = $object->tipTrigger->stats;
					 */
				} else {
					$foreach = $item->sellingStation->stats;
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
		}
	}
}
ksort ( $prices );
foreach ( $prices as $fillType => $fillTyleData ) {
	ksort ( $prices [$fillType] ['locations'] );
}
$smarty->assign ( 'prices', $prices );
$smarty->assign ( 'commodities', $commodities );
ksort ( $sellingPoints );
$smarty->assign ( 'sellingPoints', $sellingPoints );
function getPrice($amplitude0, $amplitude1, $period0, $period1, $time0, $time1, $nominalAmplitude1) {
	$sin1 = $amplitude0 * sin ( (2 * pi () / $period0) * $time0 );
	$sin2 = $amplitude1 * sin ( (2 * pi () / $period1) * $time1 ) + $nominalAmplitude1 * 10;
	return ($sin1 + $sin2) * 1000;
}
function getPrices($curve0, $curve1) {
	$curve0 = objects2float ( $curve0 );
	$curve1 = objects2float ( $curve1 );
	$offset = 1000;
	$currentPrice = getPrice ( $curve0 ['amplitude'], $curve1 ['amplitude'], $curve0 ['period'], $curve1 ['period'], $curve0 ['time'], $curve1 ['time'], $curve1 ['nominalAmplitude'] );
	$nextPrice = getPrice ( $curve0 ['amplitude'], $curve1 ['amplitude'], $curve0 ['period'], $curve1 ['period'], $curve0 ['time'] + $offset, $curve1 ['time'] + $offset, $curve1 ['nominalAmplitude'] );
	$maxPrice = ($curve0 ['nominalAmplitude'] + $curve0 ['nominalAmplitudeVariation'] + $curve1 ['nominalAmplitude'] + $curve1 ['nominalAmplitudeVariation'] + $curve1 ['nominalAmplitude'] * 10) * 1000;
	$minPrice = (($curve0 ['nominalAmplitude'] + $curve0 ['nominalAmplitudeVariation'] + $curve1 ['nominalAmplitude'] + $curve1 ['nominalAmplitudeVariation']) * - 1 + $curve1 ['nominalAmplitude'] * 10) * 1000;
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