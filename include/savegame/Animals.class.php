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
				self::$stables [$l_stable] = array (
						'i3dName' => $stable,
						'productivity' => $productivity,
						'animals' => array (),
						'state' => array () 
				
				);
				foreach ( $item->module as $module ) {
					switch ($module ['name']) {
						case 'animals' :
							foreach ( $module->animal as $animal ) {
								$animal = strval ( $animal ['fillType'] );
								$l_animal = translate ( $animal );
								if (isset ( self::$stables [$l_stable] ['animals'] [$l_animal] )) {
									self::$stables [$l_stable] ['animals'] [$l_animal] ['count'] ++;
								} else {
									self::$stables [$l_stable] ['animals'] [$l_animal] = array (
											'i3dName' => $animal,
											'count' => 1,
											'breeding' => 0,
											'reproRate' => 0,
											'nextAnimal' => 0 
									);
								}
							}
							foreach ( $module->breeding as $breeding ) {
								$count = self::$stables [$l_stable] ['animals'] [$l_animal] ['count'];
								if ($count > 1 && $productivity != 0) {
									$animal = strval ( $breeding ['fillType'] );
									$l_animal = translate ( $animal );
									$breeding = floatval ( $breeding ['percentage'] );
									$reproRate = intval ( self::getReproRate ( $animal ) / $count * 3600 * 100 / $productivity );
									self::$stables [$l_stable] ['animals'] [$l_animal] ['breeding'] = gmdate ( "H:i", $reproRate );
									self::$stables [$l_stable] ['animals'] [$l_animal] ['nextAnimal'] = gmdate ( "H:i", $reproRate * (1 - $breeding) );
								}
							}
							break;
						case 'foodSpillage' :
							$cleanlinessFactor = floatval ( $module ['cleanlinessFactor'] );
							self::$stables [$l_stable] ['##CLEANLINESS##'] = array (
									'value' => $cleanlinessFactor,
									'unit' => '%',
									'factor' => $cleanlinessFactor 
							);
							break;
						case 'straw' :
							$fillCapacity = floatval ( $module ['fillCapacity'] );
							$fillLevel = floatval ( $module->fillLevel ['fillLevel'] );
							$factor = $fillLevel / $fillCapacity;
							self::$stables [$l_stable] [translate ( 'STRAW' )] = array (
									'value' => $fillLevel,
									'unit' => 'l',
									'factor' => $factor 
							);
							break;
						case 'water' :
							$fillCapacity = floatval ( $module ['fillCapacity'] );
							$fillLevel = floatval ( $module->fillLevel ['fillLevel'] );
							$factor = $fillLevel / $fillCapacity;
							self::$stables [$l_stable] [translate ( 'WATER' )] = array (
									'value' => $fillLevel,
									'unit' => 'l',
									'factor' => $factor
							);
							break;
						case 'liquidManure' :
							$fillLevel = intval ( $module->fillLevel ['fillLevel'] );
							break;
						case 'milk' :
							$fillLevel = intval ( $module->fillLevel ['fillLevel'] );
							break;
					}
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
			return 666;
		}
		return 999;
	}
}