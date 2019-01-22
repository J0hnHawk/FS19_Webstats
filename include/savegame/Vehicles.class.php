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
	const DEFAULT_LEASING_DEPOSIT_FACTOR = 0.02;
	const DEFAULT_RUNNING_LEASING_FACTOR = 0.021;
	const PER_DAY_LEASING_FACTOR = 0.01;
	const LIFETIME_OPERATINGTIME_RATIO = 0.08333;
	const MAX_DAILYUPKEEP_MULTIPLIER = 4;
	const DIRECT_SELL_MULTIPLIER = 1.2;
	private $name;
	private $age;
	private $lifetime = 600;
	private $wear;
	private $price;
	private $resale;
	private $propertyState;
	private $operatingTime;
	private $operatingTimeString;
	private static $vehicles = array ();
	private static $buildings = array ();
	private static $buildingsArray = array ();
	private static $vehiclesResaleSum = 0;
	private static $buildingsResaleSum = 0;
	// private static $pallets = array ();
	public static function extractXML($xml, $farmId, $pallets) {
		foreach ( $xml ['vehicles'] as $vehicleInXML ) {
			if ($vehicleInXML ['farmId'] != $farmId) {
				continue;
			}
			$filename = cleanFileName ( $vehicleInXML ['filename'] );
			$vehicle = new Vehicle ();
			$vehicle->name = translate ( $filename );
			$vehicle->age = intval ( $vehicleInXML ['age'] );
			if (isset ( $vehicleInXML->wearable )) {
				$wearnode = (1 - floatval ( $vehicleInXML->wearable->wearNode ['amount'] )) * 100;
			} else {
				$wearnode = 100;
			}
			$vehicle->wear = $wearnode;
			$vehicle->price = intval ( $vehicleInXML ['price'] );
			$vehicle->operatingTime = floatval ( $vehicleInXML ['operatingTime'] );
			$vehicle->resale = self::getSellPrice ( $vehicle->price, $vehicle->lifetime, $vehicle->age, $vehicle->operatingTime );
			$vehicle->operatingTimeString = self::getOperatingTimeString ( $vehicle->operatingTime );
			$vehicle->propertyState = intval ( $vehicleInXML ['propertyState'] );
			if ($vehicle->propertyState == 2) {
				$vehicle->leasingDepositCost = self::getLeasingDepositCost ( $vehicle->price );
				$vehicle->leasingCostPerHour = self::getLeasingCostPerHour ( $vehicle->price );
				$vehicle->dayLeasingCost = self::getDayLeasingCost ( $vehicle->price );
				$vehicle->leasingCost = self::getLeasingCost ( $vehicle->price, $vehicle->age, $vehicle->operatingTime );
			}
			if (in_array ( $filename, $pallets )) {
				// self::$pallets [] = get_object_vars ( $vehicle );
			} else {
				self::$vehicles [] = get_object_vars ( $vehicle );
				self::$vehiclesResaleSum += ($vehicle->propertyState == 1) ? $vehicle->resale : 0;
			}
		}
		foreach ( $xml ['items'] as $item ) {
			if ($item ['farmId'] != $farmId || strval ( $item ['className'] ) == 'Bale') {
				continue;
			}
			$filename = cleanFileName ( $item ['filename'] );
			$building = new Vehicle ();
			$building->name = translate ( $filename );
			$building->age = intval ( $item ['age'] );
			$building->lifetime = 1000;
			$building->price = intval ( $item ['price'] );
			$building->resale = self::getBuildingSellPrice ( $building->price, $building->lifetime, $building->age, 0 );
			self::$buildings [] = $building;
			self::$buildingsArray [] = get_object_vars ( $building );
			self::$buildingsResaleSum += $building->resale;
		}
	}
	public static function getAllVehicles() {
		return self::$vehicles;
	}
	public static function getAllBuildings() {
		return self::$buildingsArray;
	}
	public static function getVehiclesResaleSum() {
		return self::$vehiclesResaleSum;
	}
	public static function getBuildingsResaleSum() {
		return self::$buildingsResaleSum;
	}
	private static function getSellPrice($price, $maxVehicleAge, $age, $operatingTime) {
		$priceMultiplier = 0.75;
		if ($maxVehicleAge != null && $maxVehicleAge != 0) {
			$ageMultiplier = 0.5 * min ( $age / $maxVehicleAge, 1 );
			$operatingTime = $operatingTime / 1000;
			$operatingTimeMultiplier = 0.5 * min ( $operatingTime / ($maxVehicleAge * self::LIFETIME_OPERATINGTIME_RATIO), 1 );
			$priceMultiplier = $priceMultiplier * exp ( - 3.5 * ($ageMultiplier + $operatingTimeMultiplier) );
		}
		return floor ( $price * max ( $priceMultiplier, 0.05 ) );
	}
	private static function getBuildingSellPrice($price, $maxVehicleAge, $age) {
		$priceMultiplier = 0.5;
		if ($maxVehicleAge != null && $maxVehicleAge != 0) {
			$priceMultiplier = $priceMultiplier * exp ( - 3.5 * min ( $age / $maxVehicleAge, 1 ) );
		}
		return floor ( $price * max ( $priceMultiplier, 0.05 ) );
	}
	private static function getOperatingTimeString($operatingTime) {
		$hours = (gmdate ( "j", $operatingTime ) - 1) * 24 + gmdate ( "H", $operatingTime );
		$minutes = gmdate ( "i", $operatingTime );
		return "$hours:$minutes";
	}
	private static function getLeasingCost($price, $age, $operatingTime) {
		$depositeCost = self::getLeasingDepositCost ( $price );
		$costHours = self::getLeasingCostPerHour ( $price ) * ($operatingTime / 60 / 60);
		$costDays = self::getDayLeasingCost ( $price ) * $age;
		return $depositeCost + $costHours + $costDays;
	}
	private static function getLeasingDepositCost($price) {
		return floor ( $price * self::DEFAULT_LEASING_DEPOSIT_FACTOR );
	}
	private static function getLeasingCostPerHour($price) {
		return floor ( $price * self::DEFAULT_RUNNING_LEASING_FACTOR );
	}
	private static function getDayLeasingCost($price) {
		return floor ( $price * self::PER_DAY_LEASING_FACTOR );
	}
	private static function getDailyUpkeep($vehicle) {
		$multiplier = 1;
		if ($vehicle->lifetime != null && $vehicle->lifetime != 0) {
			$ageMultiplier = 0.3 * min ( $vehicle->age / $vehicle->lifetime, 1 );
			$operatingTime = $vehicle->operatingTime / 1000;
			$operatingTimeMultiplier = 0.7 * min ( $operatingTime / ($vehicle->lifetime * self::LIFETIME_OPERATINGTIME_RATIO), 1 );
			$multiplier = 1 + self::MAX_DAILYUPKEEP_MULTIPLIER * ($ageMultiplier + $operatingTimeMultiplier);
		}
		return $vehicle->dailyUpkeep * $multiplier;
	}
}