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
class Mission {
	private $type;
	private $reward;
	private $status;
	private $success;
	private static $missions = array ();
	public static function extractXML($xml) {
		foreach ( $xml as $missionInXML ) {
			$mission = new Mission ();
			$mission->type = sprintf ( '##MIS_%s##', strtoupper ( strval ( $missionInXML ['type'] ) ) );
			$mission->reward = intval ( $missionInXML ['reward'] );
			$mission->status = floatval ( $missionInXML ['status'] );
			$mission->success = get_bool ( $missionInXML ['success'] );
			if (isset ( $missionInXML ['farmId'] )) {
				$mission->farmId = intval ( $missionInXML ['farmId'] );
			}
			foreach ( $missionInXML as $details ) {
				if ($details->getName () == 'field') {
					$vehicleUseCost = intval ( $details ['vehicleUseCost'] );
					$mission->field = intval ( $details ['id'] );
					$mission->fieldSize = $vehicleUseCost / 320;
					$mission->vehicleUseCost = $vehicleUseCost;
					$mission->fruitTypeName = translate ( $details ['fruitTypeName'] );
				}
				if ($details->getName () == 'bale') {
					$mission->fruitTypeName = translate ( $details ['fillTypeName'] );
				}
			}
			self::$missions [] = get_object_vars ( $mission );
		}
	}
	public static function getAllMissions() {
		return self::$missions;
	}
}
	