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
$items = simplexml_load_file ( '../savegame_19_1/items.xml' );
foreach ( $items->item as $item ) {
	$className = strval ( $item ['className'] );
	if($className == 'SellingStationPlaceable') {
		
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