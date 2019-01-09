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
	public static $commodities;
	public static function addCommodity($fillType, $fillLevel, $location, $className = 'none', $isCombine = false) {
		$l_fillType = translate ( $fillType );
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
		if(isset($location)) {
			
		}
	}
	private function ALT($fillType, $fillLevel, $location, $className = 'none', $isCombine = false) {
		global $commodities;
		$l_fillType = translate ( $fillType );
		$l_location = translate ( $location );
		if (! isset ( $commodities [$l_fillType] )) {
			$commodities [$l_fillType] = array (
					'overall' => $fillLevel,
					'i3dName' => $fillType,
					'isCombine' => $isCombine,
					'locations' => array () 
			);
		} else {
			$commodities [$l_fillType] ['overall'] += $fillLevel;
		}
		if (isset ( $location )) {
			$l_location = translate ( $location );
			if (! isset ( $commodities [$l_fillType] ['locations'] [$l_location] )) {
				$commodities [$l_fillType] ['locations'] += array (
						$l_location => array (
								'i3dName' => $location,
								$className => 1,
								'fillLevel' => $fillLevel 
						) 
				);
			} else {
				if (! isset ( $commodities [$l_fillType] ['locations'] [$l_location] [$className] )) {
					$commodities [$l_fillType] ['locations'] [$l_location] [$className] = 1;
				} else {
					$commodities [$l_fillType] ['locations'] [$l_location] [$className] ++;
				}
				$commodities [$l_fillType] ['locations'] [$l_location] ['fillLevel'] += $fillLevel;
			}
			ksort ( $commodities [$l_fillType] ['locations'] );
		}
	}
}

