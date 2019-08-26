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
	private static $priceMultiplier;
	public static $prices = array ();
	public static $sellStations = array ();
	public static $greatDemands = array ();
	public static $greatDemandIsRunning = false;
	public static function extractXML($savegame) {
		global $gameData;
		self::loadGreatDemands ( $savegame->economy );
		self::$priceMultiplier = $savegame->getPriceMultiplier ();
		foreach ( $savegame->items as $item ) {
			$location = cleanFileName ( $item ['filename'] );
			$stationId = intval ( $item ['id'] );
			// Lager, Fabriken usw. analysieren
			if (! isset ( $gameData ['objects'] [$location] ['locationType'] )) {
				// Objekte, die nicht in der Kartenkonfiguration aufgeführt sind, werden ignoriert
				continue;
			} else {
				if (isset ( $gameData ['objects'] [$location] ['isSellingPoint'] ) && $gameData ['objects'] [$location] ['isSellingPoint']) {
					self::$sellStations [translate ( $location )] = $location;
					if ($gameData ['objects'] [$location] ['locationType'] == 'bga') {
						if ($item ['farmId'] == $savegame->farmId) {
							foreach ( $gameData ['objects'] [$location] ['prices'] as $fillType => $price ) {
								$price = floatval ( $price * $savegame->getPriceMultiplier () * 1000 );
								self::addNewPrice ( $fillType, $price, $location, $price, $price, 1, 0 );
							}
						} else {
							unset ( self::$sellStations [translate ( $location )] );
						}
					} else {
						foreach ( $item->sellingStation->stats as $triggerStats ) {
							$fillType = strval ( $triggerStats ['fillType'] );
							$isInPlateau = get_bool ( $triggerStats ['isInPlateau'] );
							extract ( self::getPrices ( $triggerStats->curveBaseCurve, $triggerStats->curve1 ) );
							if ($isInPlateau) {
								$priceTrend = 0;
							}
							$greatDemand = self::getGreatDemandMultiplier ( $fillType, $stationId );
							self::addNewPrice ( $fillType, $currentPrice, $location, $maxPrice, $minPrice, $greatDemand, $priceTrend );
						}
					}
				}
			}
		}
	}
	private static function addNewPrice($fillType, $currentPrice, $location, $maxPrice, $minPrice, $greatDemand, $priceTrend) {
		$l_fillType = translate ( $fillType );
		$l_location = translate ( $location );
		if (! isset ( self::$prices [$l_fillType] )) {
			self::$prices [$l_fillType] = array (
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
		self::$prices [$l_fillType] ['locations'] [$l_location] = array (
				'i3dName' => $location,
				'price' => $currentPrice * $greatDemand,
				'greatDemand' => ($greatDemand > 1) ? true : false,
				'maxPrice' => $maxPrice,
				'minPrice' => $minPrice,
				'priceTrend' => $priceTrend
		);
		if ($currentPrice * $greatDemand > self::$prices [$l_fillType] ['bestPrice']) {
			self::$prices [$l_fillType] ['bestPrice'] = $currentPrice * $greatDemand;
			self::$prices [$l_fillType] ['bestLocation'] = $l_location;
			self::$prices [$l_fillType] ['greatDemand'] = ($greatDemand > 1) ? true : false;
			self::$prices [$l_fillType] ['priceTrend'] = $priceTrend;
		}
		if ($maxPrice > self::$prices [$l_fillType] ['maxPrice']) {
			self::$prices [$l_fillType] ['maxPrice'] = $maxPrice;
		}
		if ($minPrice < self::$prices [$l_fillType] ['minPrice']) {
			self::$prices [$l_fillType] ['minPrice'] = $minPrice;
		}
	}
	public static function getAllPrices() {
		ksort ( self::$prices );
		return self::$prices;
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
		if (isset ( $xml->greatDemands->greatDemand )) {
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
	}
	private static function getGreatDemandMultiplier($fillType, $stationId) {
		$l_fillType = translate ( $fillType );
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
		return ($sin1 + $sin2) * 1000 * self::$priceMultiplier;
	}
	private static function getPrices($curve0, $curve1) {
		$curve0 = self::objects2float ( $curve0 );
		$curve1 = self::objects2float ( $curve1 );
		$offset = 1000;
		$currentPrice = self::getPrice ( $curve0 ['amplitude'], $curve1 ['amplitude'], $curve0 ['period'], $curve1 ['period'], $curve0 ['time'], $curve1 ['time'], $curve1 ['nominalAmplitude'] );
		$nextPrice = self::getPrice ( $curve0 ['amplitude'], $curve1 ['amplitude'], $curve0 ['period'], $curve1 ['period'], $curve0 ['time'] + $offset, $curve1 ['time'] + $offset, $curve1 ['nominalAmplitude'] );
		$maxPrice = ($curve0 ['nominalAmplitude'] + $curve0 ['nominalAmplitudeVariation'] + $curve1 ['nominalAmplitude'] + $curve1 ['nominalAmplitudeVariation'] + $curve1 ['nominalAmplitude'] * 10) * 1000 * self::$priceMultiplier;
		$minPrice = (($curve0 ['nominalAmplitude'] + $curve0 ['nominalAmplitudeVariation'] + $curve1 ['nominalAmplitude'] + $curve1 ['nominalAmplitudeVariation']) * - 1 + $curve1 ['nominalAmplitude'] * 10) * 1000 * self::$priceMultiplier;
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