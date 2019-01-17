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
class Farm {
	private $name;
	private $color;
	private $loan;
	private $money;
	private $players = array ();
	private $contractFrom = array ();
	private $contractWith = array ();
	private static $farms = array ();
	private static $farmsArray = array ();
	public static function extractXML($xml) {
		foreach ( $xml ['farms'] as $farmInXML ) {
			$farmId = intval ( $farmInXML ['farmId'] );
			$farm = new Farm ();
			$farm->name = strval ( $farmInXML ['name'] );
			$farm->color = intval ( $farmInXML ['color'] );
			$farm->loan = intval ( $farmInXML ['loan'] );
			$farm->money = floatval ( $farmInXML ['money'] );
			if (isset ( $farmInXML->players )) {
				foreach ( $farmInXML->players->player as $player ) {
					$farm->players [] = array (
							'name' => strval ( $player ['lastNickname'] ),
							'isFarmManager' => get_bool ( $player ['farmManager'] ) 
					);
				}
			}
			if (isset ( $farmInXML->contracting )) {
				foreach ( $farmInXML->contracting->farm as $contractingFarm ) {
					$farm->contractFrom [intval ( $contractingFarm ['farmId'] )] = true;
				}
			}
			self::$farms [$farmId] = $farm;
			self::$farmsArray [$farmId] = get_object_vars ( $farm );
		}
		foreach ( self::$farms as $farmId1 => $farm ) {
			foreach ( $farm->contractFrom as $farmId2 => $bool ) {
				self::$farms [$farmId2]->contractWith [$farmId1] = true;
				self::$farmsArray [$farmId2] ['contractWith'] [$farmId1] = true;
			}
		}
	}
	public static function getAllFarms() {
		return self::$farmsArray;
	}
	public static function getMoney($farmId) {
		$farm = self::$farms [$farmId];
		return $farm->money;
	}
	public static function getName($farmId) {
		$farm = self::$farms [$farmId];
		return $farm->name;
	}
	public static function getLoan($farmId) {
		$farm = self::$farms [$farmId];
		return $farm->loan;
	}
}
	