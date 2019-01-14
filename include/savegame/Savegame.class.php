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
class Savegame {
	const CONFIG_CHANGE_PRICE = 1000;
	const COST_MULTIPLIER = array (
			1 => 0.4,
			2 => 0.7,
			3 => 1 
	);
	const MAX_DAILYUPKEEP_MULTIPLIER = 4;
	const MAX_GREAT_DEMANDS = 3;
	const PRICE_DROP_MIN_PERCENT = 0.6;
	const PRICE_MULTIPLIER = array (
			1 => 3,
			2 => 1.8,
			3 => 1 
	);
	public $commodities = array ();
	public $outOfMap = array ();
	public $positions = array ();
	public static $xml = array ();
	protected $xmlFiles = array (
			'environment.xml',
			'economy.xml',
			'farms.xml',
			'items.xml',
			'missions.xml',
			'vehicles.xml' 
	);
	private $farmId;
	private $ftp = array ();
	private $cache = './cache/';
	public function __construct($farmId = 0) {
		$config = file ( './config/server.conf' );
		$config = unserialize ( $config [0] );
		if (! file_exists ( $this->cache )) {
			mkdir ( $this->cache );
		}
		$this->farmId = $farmId;
		switch ($config ['type']) {
			case 'ftp' :
				$this->ftp ['server'] = $config ['server'];
				$this->ftp ['port'] = $config ['port'];
				$this->ftp ['ssl'] = $config ['ssl'];
				$this->ftp ['path'] = $config ['path'];
				$this->ftp ['user'] = $config ['user'];
				$this->ftp ['pass'] = $config ['pass'];
				$this->ftp ['isgportal'] = $config ['isgportal'];
				$updateFiles = true;
				if (file_exists ( $this->cache . $this->xmlFiles [0] )) {
					self::$xml [basename ( $this->xmlFiles [0], '.xml' )] = simplexml_load_file ( $this->cache . $this->xmlFiles [0] );
					$lastDayTime = intval ( self::$xml ['environment']->currentDay ) * 86400 + intval ( self::$xml ['environment']->dayTime * 60 );
					$this->getFileByFTP ( $this->xmlFiles [0] );
					$careerEnvironment = simplexml_load_file ( $this->cache . $this->xmlFiles [0] );
					$newDayTime = intval ( $careerEnvironment->currentDay ) * 86400 + intval ( $careerEnvironment->dayTime * 60 );
					if ($newDayTime == $lastDayTime) {
						$updateFiles = false;
					}
				} else {
					$this->getFileByFTP ( $this->xmlFiles [0] );
				}
				self::$xml [basename ( $this->xmlFiles [0], '.xml' )] = simplexml_load_file ( $this->cache . $this->xmlFiles [0] );
				for($s1 = 1; $s1 < sizeof ( $this->xmlFiles ); $s1 ++) {
					if ($updateFiles) {
						$this->getFileByFTP ( $this->xmlFiles [$s1] );
					}
					self::$xml [basename ( $this->xmlFiles [$s1], '.xml' )] = simplexml_load_file ( $this->cache . $this->xmlFiles [$s1] );
				}
				break;
			case 'local' :
				$this->cache = $config ['path'];
				for($s1 = 0; $s1 < sizeof ( $this->xmlFiles ); $s1 ++) {
					self::$xml [basename ( $this->xmlFiles [$s1], '.xml' )] = simplexml_load_file ( $this->cache . $this->xmlFiles [$s1] );
				}
				break;
			case 'api' :
				break;
		}
		$this->loadInitialData ();
	}
	public function getCurrentDay() {
		return intval ( self::$xml ['environment']->currentDay );
	}
	public function getDayTime() {
		return gmdate ( "H:i", floatval ( self::$xml ['environment']->dayTime ) * 60 );
	}
	public function getFarmMoney($farmId) {
		foreach ( self::$xml ['farms'] as $farmInXML ) {
			if (intval ( $farmInXML ['farmId'] ) == $farmId) {
				return floatval ( $farmInXML ['money'] );
			}
		}
		return false;
	}
	public static function getXML($xml) {
		return self::$xml [$xml];
	}
	private function loadInitialData() {
		if ($this->farmId) {
			$this->loadVehicles ();
			$this->loadCommodities ();
		}
	}
	private function loadCommodities() {
		global $mapconfig;
		foreach ( self::$xml ['items'] as $item ) {
			// Ballen usw.
			$className = strval ( $item ['className'] );
			$fillType = false;
			$location = getLocation ( $item ['position'] );
			if (stristr ( $className, 'pallet' ) !== false || $className == 'Bale') {
				if (isset ( $item ['i3dFilename'] )) {
					$fillType = cleanFileName ( $item ['i3dFilename'] );
				} else {
					$fillType = cleanFileName ( $item ['filename'] );
				}
				if ($fillType && strval ( $item ['farmId'] ) == $this->farmId) {
					$fillLevel = intval ( $item ['fillLevel'] );
					$this->addCommodity ( $fillType, $fillLevel, $location, $className );
					if ($location == 'outOfMap') {
						$this->commodities [translate ( $fillType )] ['outOfMap'] = true;
						// für Modal Dialog mit Edit-Vorschlag, Platzierung beim Palettenlager
						$this->outOfMap [] = array (
								$className,
								$fillType,
								strval ( $item ['position'] ),
								'-870 100 ' . (- 560 + sizeof ( $outOfMap ) * 2) 
						);
					} else {
						$this->positions [$className] [translate ( $fillType )] [] = array (
								'name' => $className,
								'position' => explode ( ' ', $item ['position'] ) 
						);
					}
				}
			}
			// Bahnsilos
			$location = cleanFileName ( $item ['filename'] );
			$stationId = intval ( $item ['id'] );
			// Lager, Fabriken usw. analysieren
			if (! isset ( $mapconfig [$location] ['locationType'] )) {
				// Objekte, die nicht in der Kartenkonfiguration aufgeführt sind, werden ignoriert
				// echo ("$location<br>");
				continue;
			}
			if ($mapconfig [$location] ['locationType'] == 'storage') {
				foreach ( $item as $storage ) {
					if (strval ( $storage ['farmId'] ) == $this->farmId) {
						foreach ( $storage as $node ) {
							$fillType = strval ( $node ['fillType'] );
							$fillLevel = intval ( $node ['fillLevel'] );
							$this->addCommodity ( $fillType, $fillLevel, $location );
						}
					}
				}
			}
		}
	}
	private function addCommodity($fillType, $fillLevel, $location, $className = 'none', $isCombine = false) {
		$l_fillType = translate ( $fillType );
		$l_location = translate ( $location );
		if (! isset ( $this->commodities [$l_fillType] )) {
			$this->commodities [$l_fillType] = array (
					'overall' => $fillLevel,
					'i3dName' => $fillType,
					'isCombine' => $isCombine,
					'locations' => array () 
			);
		} else {
			$this->commodities [$l_fillType] ['overall'] += $fillLevel;
		}
		//if (isset ( $location )) {
			$l_location = translate ( $location );
			if (! isset ( $this->commodities [$l_fillType] ['locations'] [$l_location] )) {
				$this->commodities [$l_fillType] ['locations'] += array (
						$l_location => array (
								'i3dName' => $location,
								$className => 1,
								'fillLevel' => $fillLevel 
						) 
				);
			} else {
				if (! isset ( $this->commodities [$l_fillType] ['locations'] [$l_location] [$className] )) {
					$this->commodities [$l_fillType] ['locations'] [$l_location] [$className] = 1;
				} else {
					$this->commodities [$l_fillType] ['locations'] [$l_location] [$className] ++;
				}
				$this->commodities [$l_fillType] ['locations'] [$l_location] ['fillLevel'] += $fillLevel;
			}
			ksort ( $this->commodities [$l_fillType] ['locations'] );
		//}
	}
	private function loadVehicles() {
		global $mapconfig;
		foreach ( self::$xml ['vehicles'] as $vehicle ) {
			$propertyState = 1;
			if ($vehicle ['farmId'] != $this->farmId) {
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
				if (isset ( $vehicle->wearable )) {
					$wearnode = (1 - floatval ( $vehicle->wearable->wearNode ['amount'] )) * 100;
				} else {
					$wearnode = 100;
				}
				$operatingTime = floatval ( $vehicle ['operatingTime'] );
				$opHours = (gmdate ( "j", $operatingTime ) - 1) * 24 + gmdate ( "H", $operatingTime );
				$opMinutes = gmdate ( "i", $operatingTime );
				$propertyState = intval ( $vehicle ['propertyState'] );
			}
			if (isset ( $vehicle->fillUnit ) && $propertyState != 3) {
				foreach ( $vehicle->fillUnit->unit as $unit ) {
					$fillType = strval ( $unit ['fillType'] );
					$fillLevel = intval ( $unit ['fillLevel'] );
					if ($fillType != 'UNKNOWN' && $fillType != 'SQUAREBALE' && $fillType != 'AIR' && $fillType != 'DEF') {
						$this->addCommodity ( $fillType, $fillLevel, $location, $className );
					}
				}
			}
		}
	}
	private function getFileByFTP($file) {
		if ($this->ftp ['isgportal']) {
			$URL = "ftp://" . $this->ftp ['user'] . ":" . $this->ftp ['pass'] . "@" . $this->ftp ['server'] . ":" . $this->ftp ['port'] . $this->ftp ['path'];
			$fp = fopen ( $this->cache . $file, "w" );
			$url = $URL . $file;
			$curl = curl_init ();
			curl_setopt ( $curl, CURLOPT_URL, $url );
			curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 0 );
			curl_setopt ( $curl, CURLOPT_UPLOAD, 0 );
			curl_setopt ( $curl, CURLOPT_FILE, $fp );
			$result = curl_exec ( $curl );
			curl_close ( $curl );
			fclose ( $fp );
		} else {
			$local_file = $this->cache . $file;
			$server_file = $this->ftp ['path'] . $file;
			if ($this->ftp ['ssl']) {
				$ftp_conn = ftp_ssl_connect ( $this->ftp ['server'], $this->ftp ['port'], 10 );
			} else {
				$ftp_conn = ftp_connect ( $this->ftp ['server'], $this->ftp ['port'], 10 );
			}
			if (! $ftp_conn) {
				echo ("Verbindung fehlgeschlagen<br>\r\n");
			} else {
				if (! ftp_login ( $ftp_conn, $this->ftp ['user'], $this->ftp ['pass'] )) {
					echo ("Login fehlgeschlagen<br>\r\n");
				} else {
					if (! ftp_pasv ( $ftp_conn, true )) {
						echo ("Umschalten in passiven Modus fehlgeschlagen<br>\r\n");
					} else {
						if (! ftp_get ( $ftp_conn, $local_file, $server_file, FTP_ASCII )) {
							echo ("Download von '$server_file' fehlgeschlagen<br>\r\n");
						}
					}
				}
				ftp_close ( $ftp_conn );
			}
		}
	}
}