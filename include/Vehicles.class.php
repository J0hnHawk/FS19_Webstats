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
class Vehicle {
	const DEPOPFACTOR = - 0.035001691;
	const DEPAGEFACTOR = - 0.002916669;
	const LIFETIME_OPERATINGTIME_RATIO = 0.08333;
	private $name;
	private $age;
	private $lifetime = 600;
	private $wear;
	private $price;
	private $resale1;
	private $resale2;
	private $resale3;
	private $propertyState;
	private $operatingTime;
	private $operatingTimeString;
	private static $vehicles = array ();
	private static $pallets = array ();
	public static function extractXML($xml, $farmId, $pallets) {
		foreach ( $xml as $vehicleInXML ) {
			if ($vehicleInXML ['farmId'] != $farmId) {
				continue;
			}
			$vehicle = new Vehicle ();
			$vehicle->name = cleanFileName ( $vehicleInXML ['filename'] );
			$vehicle->age = intval ( $vehicleInXML ['age'] );
			if (isset ( $vehicleInXML->wearable )) {
				$wearnode = (1 - floatval ( $vehicleInXML->wearable->wearNode ['amount'] )) * 100;
			} else {
				$wearnode = 100;
			}
			$vehicle->wear = $wearnode;
			$vehicle->price = intval ( $vehicleInXML ['price'] );
			$vehicle->operatingTime = floatval ( $vehicleInXML ['operatingTime'] );
			$operatingHours = $vehicle->operatingTime / 60 / 60;
			$vehicle->resale1 = $vehicle->price * 0.9 * exp ( (($operatingHours < 50) ? $operatingHours : 50) * self::DEPOPFACTOR ) * exp ( (($vehicle->age < $vehicle->lifetime) ? $vehicle->age : $vehicle->lifetime) * self::DEPAGEFACTOR );
			$vehicle->resale2 = $vehicle->resale1 / 1.2; // direct sale - not at the shop
			$vehicle->resale3 = self::getSellPrice ( $vehicle->price, $vehicle->lifetime, $vehicle->age, $vehicle->operatingTime );
			$operatingTimeString = (gmdate ( "j", $vehicle->operatingTime ) - 1) * 24 + gmdate ( "H", $vehicle->operatingTime );
			$operatingTimeString .= ':' . gmdate ( "i", $vehicle->operatingTime );
			$vehicle->operatingTimeString = $operatingTimeString;
			$vehicle->propertyState = intval ( $vehicleInXML ['propertyState'] );
			if (in_array ( $vehicle->name, $pallets )) {
				self::$pallets [] = get_object_vars ( $vehicle );
			} else {
				self::$vehicles [] = get_object_vars ( $vehicle );
			}
		}
	}
	private static function getSellPrice($price, $maxVehicleAge, $age, $operatingTime) {
		$priceMultiplier = 0.75;
		if ($maxVehicleAge != null and $maxVehicleAge != 0) {
			$ageMultiplier = 0.5 * min ( $age / $maxVehicleAge, 1 );
			$operatingTime = $operatingTime / (1000 * 60 * 60);
			$operatingTimeMultiplier = 0.5 * min ( $operatingTime / ($maxVehicleAge * self::LIFETIME_OPERATINGTIME_RATIO), 1 );
			$priceMultiplier = $priceMultiplier * exp ( - 3.5 * ($ageMultiplier + $operatingTimeMultiplier) );
		}
		return $price * max ( $priceMultiplier, 0.05 );
	}
	public static function getAllVehicles() {
		return self::$vehicles;
	}
}