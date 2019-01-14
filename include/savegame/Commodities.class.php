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
	private $overall;
	private $i3dName;
	private $isCombine;
	private $locations;
	private $outOfMap = false;
	private static $xml;
	private static $farmId;
	public static $commodities;
	public static $outOfMap2 = array ();
	public static $positions = array ();
	public static function loadCommodities($xml) {
		global $mapconfig;
		self::$farmId = $_SESSION ['farmId'];
		self::$xml = $xml;
		self::loadBales ();
	}
	private static function loadBales() {
		foreach ( self::$xml ['items'] as $item ) {
			$className = strval ( $item ['className'] );
			if ($className == 'Bale') {
				$location = getLocation ( $item ['position'] );
				$fillType = cleanFileName ( $item ['filename'] );
				if (strval ( $item ['farmId'] ) == self::$farmId) {
					$fillLevel = intval ( $item ['fillLevel'] );
					self::addCommodity ( $fillType, $fillLevel, $location, $className );
					if ($location == 'outOfMap') {
						self::$commodities [translate ( $fillType )] ['outOfMap'] = true;
						// für Modal Dialog mit Edit-Vorschlag, Platzierung beim Palettenlager
						self::$outOfMap2 [] = array (
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
	}
	public static function addCommodity($fillType, $fillLevel, $location, $className = 'none', $isCombine = false) {
		$l_fillType = translate ( $fillType );
		$l_location = translate ( $location );
		if (! isset ( self::$commodities [$l_fillType] )) {
			$commodity = new Commodity ();
			$commodity->overall = $fillLevel;
			$commodity->i3dName = $fillType;
			$commodity->isCombine = $isCombine;
			$commodity->locations = array ();
			self::$commodities [$l_fillType] = $commodity;
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
				$commodity->locations [$l_location] [$l_location] [$className] = 1;
			} else {
				$commodity->locations [$l_location] [$l_location] [$className] ++;
			}
			$commodity->locations [$l_location] ['fillLevel'] += $fillLevel;
		}
		self::$commodities [$l_fillType] = $commodity;
		ksort ( self::$commodities [$l_fillType] ['locations'] );
	}
	private static function getCommoditiesArray() {
		$json = json_encode ( self::$commodities );
		return json_decode ( $json, TRUE );
	}
}

