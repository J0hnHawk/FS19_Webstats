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
class Commodity {
	const FILLTYPES_TO_IGNORE = array (
			'AIR',
			'DEF',
			'ROUNDBALE',
			'SQUAREBALE',
			'UNKNOWN' 
	);
	private $overall;
	private $i3dName;
	private $isCombine;
	private $locations;
	private $outOfMap = false;
	private static $xml;
	private static $farmId;
	public static $commodities = array ();
	public static $commoditiesArray = array ();
	public static $outOfMapArray = array ();
	public static $positions = array ();
	public static function loadCommodities($xml) {
		self::$farmId = $_SESSION ['farmId'];
		self::$xml = $xml;
		self::loadBales ();
		self::loadSilos ();
		self::loadVehicles ();
	}
	public static function getAllCommodities() {
		return self::$commoditiesArray;
	}
	public static function getAllOutOfMap() {
		return self::$outOfMapArray;
	}
	private static function loadBales() {
		foreach ( self::$xml ['items'] as $item ) {
			$className = strval ( $item ['className'] );
			if ($className == 'Bale' && strval ( $item ['farmId'] ) == self::$farmId) {
				$location = getLocation ( $item ['position'] );
				$fillType = cleanFileName ( $item ['filename'] );
				$fillLevel = intval ( $item ['fillLevel'] );
				self::addCommodity ( $fillType, $fillLevel, $location, $className );
				if ($location == 'outOfMap') {
					self::$commodities [translate ( $fillType )] ['outOfMap'] = true;
					// für Modal Dialog mit Edit-Vorschlag, Platzierung beim Palettenlager
					self::$outOfMapArray [] = array (
							$className,
							$fillType,
							strval ( $item ['position'] ),
							'-870 100 ' . (- 560 + sizeof ( $outOfMap ) * 2) 
					);
				} else {
					self::$positions [$className] [translate ( $fillType )] [] = array (
							'name' => $className,
							'position' => explode ( ' ', $item ['position'] ) 
					);
				}
			}
		}
	}
	private static function loadSilos() {
		global $mapconfig;
		foreach ( self::$xml ['items'] as $item ) {
			$location = cleanFileName ( $item ['filename'] );
			$stationId = intval ( $item ['id'] );
			if (isset ( $mapconfig [$location] ['locationType'] ) && $mapconfig [$location] ['locationType'] == 'storage') {
				foreach ( $item as $storage ) {
					if (strval ( $storage ['farmId'] ) == self::$farmId) {
						foreach ( $storage as $node ) {
							$fillType = strval ( $node ['fillType'] );
							$fillLevel = intval ( $node ['fillLevel'] );
							self::addCommodity ( $fillType, $fillLevel, $location );
						}
					}
				}
			}
		}
	}
	private static function loadVehicles() {
		global $mapconfig;
		foreach ( self::$xml ['vehicles'] as $vehicle ) {
			if ($vehicle ['farmId'] != self::$farmId) {
				continue;
			}
			$vehicleName = cleanFileName ( $vehicle ['filename'] );
			if (in_array ( $vehicleName, $mapconfig ['pallets'] )) {
				// Palette
				$location = getLocation ( $vehicle->component1 ['position'] );
				$className = 'FillablePallet';
			} else {
				// Fahrzeug
				$location = $vehicleName;
				$className = 'isVehicle';
				$vehicleId = intval ( $vehicle ['id'] );
			}
			if (isset ( $vehicle->fillUnit )) {
				foreach ( $vehicle->fillUnit->unit as $unit ) {
					$fillType = strval ( $unit ['fillType'] );
					$fillLevel = intval ( $unit ['fillLevel'] );
					if (! in_array ( $fillType, self::FILLTYPES_TO_IGNORE )) {
						self::addCommodity ( $fillType, $fillLevel, $location, $className );
					}
				}
			}
		}
	}
	private static function addCommodity($fillType, $fillLevel, $location, $className = 'none', $isCombine = false) {
		$l_fillType = translate ( $fillType );
		$l_location = translate ( $location );
		if (! isset ( self::$commodities [$l_fillType] )) {
			$commodity = new Commodity ();
			$commodity->overall = $fillLevel;
			$commodity->i3dName = $fillType;
			$commodity->isCombine = $isCombine;
			$commodity->locations = array ();
		} else {
			$commodity = self::$commodities [$l_fillType];
			$commodity->overall += $fillLevel;
		}
		if (! isset ( $commodity->locations [$l_location] )) {
			$commodity->locations += array (
					$l_location => array (
							'i3dname' => $location,
							$className => 1,
							'fillLevel' => $fillLevel 
					) 
			);
		} else {
			if (! isset ( $commodity->locations [$l_location] [$className] )) {
				$commodity->locations [$l_location] [$className] = 1;
			} else {
				$commodity->locations [$l_location] [$className] ++;
			}
			$commodity->locations [$l_location] ['fillLevel'] += $fillLevel;
		}
		self::$commodities [$l_fillType] = $commodity;
		self::$commoditiesArray [$l_fillType] = get_object_vars ( $commodity );
		// ksort ( self::$commodities [$l_fillType] ['locations'] );
	}
}

