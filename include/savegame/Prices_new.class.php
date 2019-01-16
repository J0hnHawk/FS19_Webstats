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
class Price {
	private $name;
	private $i3dName;
	private $bestPrice = 0;
	private $maxPrice = 0;
	private $minPrice = 65535;
	private $priceTrend = 0;
	private $bestLocation;
	private $greatDemand = false;
	private $locations = array ();
	public static $prices = array ();
	public static $pricesArray = array ();
	public static $sellStations = array ();
	public static $greatDemands = array ();
	public static $greatDemandIsRunning = false;
	public static function extractXML($xml) {
		global $mapconfig;
		self::loadGreatDemands ( $xml ['economy'] );
		foreach ( $xml ['items'] as $item ) {
			$location = cleanFileName ( $item ['filename'] );
			$stationId = intval ( $item ['id'] );
			// Lager, Fabriken usw. analysieren
			if (! isset ( $mapconfig [$location] ['locationType'] )) {
				// Objekte, die nicht in der Kartenkonfiguration aufgeführt sind, werden ignoriert
				continue;
			} else {
				if (isset ( $mapconfig [$location] ['isSellingPoint'] ) && $mapconfig [$location] ['isSellingPoint']) {
					$l_location = translate ( $location );
					self::$sellStations [$l_location] = $location;
					if ($mapconfig [$location] ['locationType'] == 'bga') {
						$bgaPrices = array (
								'SILAGE' => 219,
								'GRASS_WINDROW' => 57,
								'DRYGRASS_WINDROW' => 57,
								'MANURE' => 219,
								'LIQUIDMANURE' => 76 
						);
						foreach ( $bgaPrices as $fillType => $currentPrice ) {
							$l_fillType = translate ( $fillType );
							$price = self::createNewPrice ( $fillType );
							$price = self::setNewLocation ( $price, $currentPrice, $location, $currentPrice, $currentPrice, 1, 0 );
						}
					} else {
						foreach ( $item->sellingStation->stats as $triggerStats ) {
							$fillType = strval ( $triggerStats ['fillType'] );
							$isInPlateau = get_bool ( $triggerStats ['isInPlateau'] );
							$l_fillType = translate ( $fillType );
							$price = self::createNewPrice ( $fillType );
							extract ( self::getPrices ( $triggerStats->curveBaseCurve, $triggerStats->curve1 ) );
							if ($isInPlateau) {
								$priceTrend = 0;
							}
							$greatDemand = self::getGreatDemandMultiplier ( $l_fillType, $stationId );							
							$price = self::setNewLocation ( $price, $currentPrice, $location, $maxPrice, $minPrice, $greatDemand, $priceTrend );
						}
					}
					self::$prices [$l_fillType] = $price;
					self::$pricesArray [$l_fillType] = get_object_vars ( $price );
				}
			}
		}
	}
	private static function setNewLocation($price, $currentPrice, $location, $maxPrice, $minPrice, $greatDemand, $priceTrend) {
		$l_location = translate ( $location );
		$price->locations += array (
				$l_location => array (
						'i3dName' => $location,
						'price' => $currentPrice * $greatDemand,
						'greatDemand' => ($greatDemand > 1) ? true : false,
						'maxPrice' => $maxPrice,
						'minPrice' => $minPrice,
						'priceTrend' => $priceTrend 
				) 
		);
		if ($currentPrice * $greatDemand > $price->bestPrice) {
			$price->bestPrice = $currentPrice * $greatDemand;
			$price->bestLocation = $l_location;
			$price->greatDemand = ($greatDemand > 1) ? true : false;
			$price->priceTrend = $priceTrend;
		}
		if ($maxPrice > $price->maxPrice) {
			$price->maxPrice = $maxPrice;
		}
		if ($minPrice < $price->minPrice) {
			$price->minPrice = $minPrice;
		}
		return $price;
	}
	private static function createNewPrice($fillType) {
		$l_fillType = translate ( $fillType );
		if (! isset ( self::$prices [$l_fillType] )) {
			$price = new Price ();
			$price->name = $l_fillType;
			$price->i3dName = $fillType;
			return $price;
		} else {
			return self::$prices [$l_fillType];
		}
	}
	public static function getAllPrices() {
		return self::$pricesArray;
	}
	public static function getSellStations() {
		return self::$sellStations;
	}
	public static function sortPricesByFillType() {
		ksort ( self::$prices );
		foreach ( self::$prices as $fillType => $fillTyleData ) {
			ksort ( self::$prices [$fillType] ['locations'] );
		}
	}
	public static function sortSellingStationsByName() {
		ksort ( self::$sellingPoints );
	}
	private static function loadGreatDemands($xml) {
		foreach ( $xml->greatDemands->greatDemand as $greatDemand ) {
			$stationId = strval ( $greatDemand ['itemId'] );
			$fillTypeName = strval ( $greatDemand ['fillTypeName'] );
			$demandMultiplier = floatval ( $greatDemand ['demandMultiplier'] );
			$isRunning = get_bool ( $greatDemand ['isRunning'] );
			self::$greatDemandIsRunning = self::$greatDemandIsRunning || $isRunning;
			$l_fillType = translate ( $fillTypeName );
			if (isset ( self::$greatDemands [$l_fillType] )) {
				self::$greatDemands [$l_fillType] ['locations'] += array (
						$stationId => array (
								'demandMultiplier' => $demandMultiplier,
								'isRunning' => $isRunning 
						) 
				);
			} else {
				self::$greatDemands [$l_fillType] = array (
						'i3dName' => $fillTypeName,
						'locations' => array (
								$stationId => array (
										'demandMultiplier' => $demandMultiplier,
										'isRunning' => $isRunning 
								) 
						) 
				);
			}
		}
	}
	private static function getGreatDemandMultiplier($l_fillType, $stationId) {
		if (isset ( self::$greatDemands [$l_fillType] ['locations'] [$stationId] )) {
			if (self::$greatDemands [$l_fillType] ['locations'] [$stationId] ['isRunning']) {
				return self::$greatDemands [$l_fillType] ['locations'] [$stationId] ['demandMultiplier'];
			}
		}
		return 1;
	}
	private static function getPrice($amplitude0, $amplitude1, $period0, $period1, $time0, $time1, $nominalAmplitude1) {
		$sin1 = $amplitude0 * sin ( (2 * pi () / $period0) * $time0 );
		$sin2 = $amplitude1 * sin ( (2 * pi () / $period1) * $time1 ) + $nominalAmplitude1 * 10;
		return ($sin1 + $sin2) * 1000;
	}
	private static function getPrices($curve0, $curve1) {
		$curve0 = self::objects2float ( $curve0 );
		$curve1 = self::objects2float ( $curve1 );
		$offset = 1000;
		$currentPrice = self::getPrice ( $curve0 ['amplitude'], $curve1 ['amplitude'], $curve0 ['period'], $curve1 ['period'], $curve0 ['time'], $curve1 ['time'], $curve1 ['nominalAmplitude'] );
		$nextPrice = self::getPrice ( $curve0 ['amplitude'], $curve1 ['amplitude'], $curve0 ['period'], $curve1 ['period'], $curve0 ['time'] + $offset, $curve1 ['time'] + $offset, $curve1 ['nominalAmplitude'] );
		$maxPrice = ($curve0 ['nominalAmplitude'] + $curve0 ['nominalAmplitudeVariation'] + $curve1 ['nominalAmplitude'] + $curve1 ['nominalAmplitudeVariation'] + $curve1 ['nominalAmplitude'] * 10) * 1000;
		$minPrice = (($curve0 ['nominalAmplitude'] + $curve0 ['nominalAmplitudeVariation'] + $curve1 ['nominalAmplitude'] + $curve1 ['nominalAmplitudeVariation']) * - 1 + $curve1 ['nominalAmplitude'] * 10) * 1000;
		return array (
				'currentPrice' => $currentPrice,
				'minPrice' => $minPrice,
				'maxPrice' => $maxPrice,
				'priceTrend' => $nextPrice > $currentPrice ? 1 : ($currentPrice > $nextPrice ? - 1 : 0) 
		);
	}
	private static function objects2float($objectArray) {
		$floatArray = array ();
		foreach ( $objectArray->attributes () as $key => $value ) {
			$floatArray [$key] = floatval ( $value );
		}
		return $floatArray;
	}
}