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
class Animals {
	private static $farmId;
	private static $xml;
	private static $stables = array ();
	public static function loadStables($xml) {
		self::$farmId = $_SESSION ['farmId'];
		self::$xml = $xml;
		self::analyzeItems ();
	}
	private static function analyzeItems() {
		foreach ( self::$xml ['items'] as $item ) {
			$stable = cleanFileName ( $item ['filename'] );
			$l_stable = translate ( $stable );
			if ($item ['className'] == 'AnimalHusbandry' && $item ['farmId'] == self::$farmId) {
				$productivity = floatval ( $item ['globalProductionFactor'] ) * 100;
				self::$stables [$stable] = array (
						'name' => $l_stable,
						'productivity' => floor ( $productivity ),
						'animals' => array (),
						'state' => array () 
				
				);
				foreach ( $item->module as $module ) {
					switch ($module ['name']) {
						case 'animals' :
							foreach ( $module->animal as $animal ) {
								$animal = strval ( $animal ['fillType'] );
								$l_animal = translate ( $animal );
								if (isset ( self::$stables [$stable] ['animals'] [$animal] )) {
									self::$stables [$stable] ['animals'] [$animal] ['count'] ++;
								} else {
									self::$stables [$stable] ['animals'] [$animal] = array (
											'name' => $l_animal,
											'count' => 1,
											'breeding' => 0,
											'reproRate' => '--:--',
											'nextAnimal' => '--:--' 
									);
								}
							}
							foreach ( $module->breeding as $breeding ) {
								$count = self::$stables [$stable] ['animals'] [$animal] ['count'];
								if ($count > 1 && $productivity != 0) {
									$animal = strval ( $breeding ['fillType'] );
									$l_animal = translate ( $animal );
									$breeding = floatval ( $breeding ['percentage'] );
									$reproRate = ceil ( (self::getReproRate ( $animal ) / $count * 3600 * 100 / $productivity) / 900 ) * 900;
									$nextAnimal = ceil ( ($reproRate * (1 - $breeding)) / 900 ) * 900;
									self::$stables [$stable] ['animals'] [$animal] ['reproRate'] = self::getTimeString ( $reproRate );
									self::$stables [$stable] ['animals'] [$animal] ['nextAnimal'] = self::getTimeString ( $nextAnimal );
								}
							}
							break;
						case 'foodSpillage' :
							$cleanlinessFactor = floatval ( $module ['cleanlinessFactor'] );
							self::$stables [$stable] ['state'] ['foodSpillage'] = array (
									'name' => '##CLEANLINESS##',
									'value' => floor ( $cleanlinessFactor * 100 ),
									'unit' => '%',
									'factor' => floor ( $cleanlinessFactor * 100 ) 
							);
							break;
						case 'straw' :
							$fillType = strtoupper ( $module ['name'] );
							$fillCapacity = floatval ( $module ['fillCapacity'] );
							$fillLevel = floatval ( $module->fillLevel ['fillLevel'] );
							$factor = floor ( $fillLevel / $fillCapacity * 100 );
							self::$stables [$stable] ['state'] [strval ( $module ['name'] )] = array (
									'name' => translate ( 'STRAW' ),
									'value' => floor ( $fillLevel ),
									'unit' => 'l',
									'factor' => $factor 
							);
							break;
						case 'water' :
							$fillCapacity = floatval ( $module ['fillCapacity'] );
							$fillLevel = floatval ( $module->fillLevel ['fillLevel'] );
							$factor = floor ( $fillLevel / $fillCapacity * 100 );
							self::$stables [$stable] ['state'] ['water'] = array (
									'name' => translate ( 'WATER' ),
									'value' => floor ( $fillLevel ),
									'unit' => 'l',
									'factor' => $factor 
							);
							break;
						case 'manure' :
							$fillCapacity = floatval ( $module ['fillCapacity'] );
							$fillLevel = floatval ( $module ['manureToDrop'] );
							$factor = floor ( $fillLevel / $fillCapacity * 100 );
							self::$stables [$stable] ['product'] ['manure'] = array (
									'name' => translate ( 'MANURE' ),
									'value' => floor ( $fillLevel ),
									'unit' => 'l',
									'factor' => $factor 
							);
							break;
						case 'liquidManure' :
							$fillCapacity = floatval ( $module ['fillCapacity'] );
							$fillLevel = floatval ( $module->fillLevel ['fillLevel'] );
							$factor = floor ( $fillLevel / $fillCapacity * 100 );
							self::$stables [$stable] ['product'] ['liquidManure'] = array (
									'name' => translate ( 'LIQUIDMANURE' ),
									'value' => floor ( $fillLevel ),
									'unit' => 'l',
									'factor' => $factor 
							);
							break;
						case 'milk' :
							$fillCapacity = floatval ( $module ['fillCapacity'] );
							$fillLevel = floatval ( $module->fillLevel ['fillLevel'] );
							$factor = floor ( $fillLevel / $fillCapacity * 100 );
							self::$stables [$stable] ['product'] ['milk'] = array (
									'name' => translate ( 'MILK' ),
									'value' => floor ( $fillLevel ),
									'unit' => 'l',
									'factor' => $factor 
							);
							break;
						case 'pallets' :
							$fillLevel = floatval ( $module ['palletFillDelta'] );
							self::$stables [$stable] ['product'] ['wool'] = array (
									'name' => translate ( 'WOOL' ),
									'value' => floor ( $fillLevel ),
									'unit' => 'l',
									'factor' => $factor 
							);
							break;
						case 'food' :
							foreach ( $module->fillLevel as $trough ) {
								$fillType = strval ( $trough ['fillType'] );
								$fillLevel = $trough ['fillLevel'];
								self::$stables [$stable] ['trough'] [$fillType] = array (
										'name' => translate ( $fillType ),
										'value' => floor ( $fillLevel ),
										'unit' => 'l',
										'factor' => 100 
								);
							}
							break;
					}
				}
				$troughs = array_keys ( self::$stables [$stable] ['trough'] );
				if (in_array ( 'MAIZE', $troughs )) {
					$stableAnimals = 'pig';
				} elseif (in_array ( 'FORAGE', $troughs )) {
					$stableAnimals = 'cow';
				} elseif (in_array ( 'OAT', $troughs )) {
					$stableAnimals = 'horse';
				} elseif (in_array ( 'GRASS_WINDROW', $troughs )) {
					$stableAnimals = 'sheep';
				} else {
					$stableAnimals = 'chicken';
				}
			}
		}
	}
	public static function getStables() {
		return self::$stables;
	}
	private static function getReproRate($animalName) {
		if (stristr ( $animalName, 'COW' ) !== false) {
			return 1200;
		}
		if (stristr ( $animalName, 'PIG' ) !== false) {
			return 144;
		}
		if (stristr ( $animalName, 'SHEEP' ) !== false) {
			return 960;
		}
		if (stristr ( $animalName, 'CHICKEN' ) !== false) {
			return 240;
		}
		return 999;
	}
	private static function getTimeString($time) {
		$hours = (gmdate ( "d", $time ) - 1) * 24 + gmdate ( "H", $time );
		return $hours . ':' . gmdate ( "i", $time );
	}
}