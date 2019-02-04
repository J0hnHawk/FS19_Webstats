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
	private $statistics = array ();
	private $finances = array ();
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
			foreach ( $farmInXML->statistics->children () as $statisticItem => $statisticValue ) {
				$statisticData = self::statisticMapping ( $statisticItem, $statisticValue );
				if ($statisticData !== false) {
					$farm->statistics [$statisticItem] = $statisticData;
				}
			}
			foreach ( $farmInXML->finances->stats as $stats ) {
				$financesDay = self::financeDayMapping ( intval ( $stats ['day'] ) );
				$farm->finances [$financesDay] = array ();
				foreach ( $stats->children () as $financeItem => $financeValue ) {
					// $financeItem = sprintf ( "##%s##", strtoupper ( $financeItem ) );
					$financeItem = $financeItem;
					$financeValue = floatval ( $financeValue );
					$farm->finances [$financesDay] [$financeItem] = $financeValue;
				}
				$farm->finances [$financesDay] ['total'] = array_sum ( $farm->finances [$financesDay] );
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
				if (isset ( self::$farms [$farmId2] )) {
					self::$farms [$farmId2]->contractWith [$farmId1] = true;
					self::$farmsArray [$farmId2] ['contractWith'] [$farmId1] = true;
				} else {
					unset ( self::$farms [$farmId1]->contractFrom [$farmId2] );
					unset ( self::$farmsArray [$farmId1] ['contractFrom'] [$farmId2] );
				}
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
	private static function statisticMapping($key, $value) {
		$statisticItem = sprintf ( "##%s##", strtoupper ( $key ) );
		$statisticValue = floatval ( $value );
		$litres = array (
				'fuelUsage',
				'seedUsage',
				'sprayUsage' 
		);
		$hectares = array (
				'workedHectares',
				'cultivatedHectares',
				'sownHectares',
				'fertilizedHectares',
				'threshedHectares',
				'plowedHectares' 
		);
		$time = array (
				'workedTime',
				'cultivatedTime',
				'sownTime',
				'fertilizedTime',
				'threshedTime',
				'plowedTime',
				'playTime' 
		);
		$ignore = array (
				'fieldJobMissionByNPC',
				'treeTypesCut',
				'revenue',
				'expenses' 
		);
		if (in_array ( $key, $ignore )) {
			return false;
		} elseif (in_array ( $key, $litres )) {
			$statisticCategory = "litres";
		} elseif (in_array ( $key, $hectares )) {
			$statisticCategory = "hectares";
		} elseif (in_array ( $key, $time )) {
			$statisticCategory = "time";
			$statisticValue = self::getWorkTimeString ( $statisticValue );
		} else {
			$statisticCategory = "count";
		}
		return array (
				'item' => $statisticItem,
				'value' => $statisticValue,
				'category' => $statisticCategory 
		);
	}
	private static function financeDayMapping($day) {
		switch ($day) {
			case 1 :
				return 4;
			case 2 :
				return 3;
			case 3 :
				return 2;
			case 4 :
				return 1;
		}
		return 0;
	}
	private static function getWorkTimeString($workTime) {
		$workTime = $workTime * 60;
		$hours = (gmdate ( "j", $workTime ) - 1) * 24 + gmdate ( "H", $workTime );
		$minutes = gmdate ( "i", $workTime );
		return "$hours:$minutes";
	}
	private static function getFarmColor($color) {
		/*
		 * rgb = srgb ^ (1 / 2.2) * 255
		 */
		$farmColors = array (
				'1' => '#89ff89',
				'2' => '#003b78',
				'3' => '#f8b61c',
				'4' => '#f1710d',
				'5' => '#c70d0d',
				'6' => '#0085ef',
				'7' => '#f24295',
				'8' => '#550d65' 
		);
		return $farmColors [$color];
	}
}
	