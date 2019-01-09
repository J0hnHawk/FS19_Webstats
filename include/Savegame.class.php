<?php
/**
 *
 * This file is part of the "FS19 Web Stats" package.
 * Copyright (C) 2017-2018 John Hawk <john.hawk@gmx.net>
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
	public $demandIsRunning = false;
	public $greatDemands = array ();
	public $missions = array ();
	public $vehicles = array ();
	public $commodities = array ();
	public $outOfMap = array ();
	public $positions = array ();
	public $farms = array ();
	public $xml = array ();
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
	public function __construct($config, $farmId = 0) {
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
					$this->xml [basename ( $this->xmlFiles [0], '.xml' )] = simplexml_load_file ( $this->cache . $this->xmlFiles [0] );
					$lastDayTime = intval ( $this->xml ['environment']->currentDay ) * 86400 + intval ( $this->xml ['environment']->dayTime * 60 );
					$this->getFileByFTP ( $this->xmlFiles [0] );
					$careerEnvironment = simplexml_load_file ( $this->cache . $this->xmlFiles [0] );
					$newDayTime = intval ( $careerEnvironment->currentDay ) * 86400 + intval ( $careerEnvironment->dayTime * 60 );
					if ($newDayTime == $lastDayTime) {
						$updateFiles = false;
					}
				} else {
					$this->getFileByFTP ( $this->xmlFiles [0] );
				}
				$this->xml [basename ( $this->xmlFiles [0], '.xml' )] = simplexml_load_file ( $this->cache . $this->xmlFiles [0] );
				for($s1 = 1; $s1 < sizeof ( $this->xmlFiles ); $s1 ++) {
					if ($updateFiles) {
						$this->getFileByFTP ( $this->xmlFiles [$s1] );
					}
					$this->xml [basename ( $this->xmlFiles [$s1], '.xml' )] = simplexml_load_file ( $this->cache . $this->xmlFiles [$s1] );
				}
				
				break;
			case 'local' :
				$this->cache = $config ['path'];
				
				break;
			case 'api' :
				break;
		}
		$this->loadInitialData ();
	}
	public function getCurrentDay() {
		return intval ( $this->xml ['environment']->currentDay );
	}
	public function getDayTime() {
		return gmdate ( "H:i", floatval ( $this->xml ['environment']->dayTime ) * 60 );
	}
	public function getFarmMoney($farmId) {
		if (isset ( $this->farms [$farmId] )) {
			return $this->farms [$farmId] ['money'];
		} else {
			return false;
		}
	}
	private function loadInitialData() {
		$this->loadFarms ();
		$this->loadMissions ();
		$this->loadGreatDemands ();
		if ($this->farmId) {
			$this->loadVehicles ();
			$this->loadCommodities ();
		}
	}
	private function loadCommodities() {
		global $mapconfig;
		foreach ( $this->xml ['items'] as $item ) {
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
		if (isset ( $location )) {
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
		}
	}
	private function loadVehicles() {
		global $mapconfig;
		foreach ( $this->xml ['vehicles'] as $vehicle ) {
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
				}
				$operatingTime = floatval ( $vehicle ['operatingTime'] );
				$opHours = (gmdate ( "j", $operatingTime ) - 1) * 24 + gmdate ( "H", $operatingTime );
				$opMinutes = gmdate ( "i", $operatingTime );
				$propertyState = intval ( $vehicle ['propertyState'] );
				$this->vehicles [$vehicleId] = array (
						'name' => $vehicleName,
						'age' => intval ( $vehicle ['age'] ),
						'wear' => $wearnode,
						'price' => intval ( $vehicle ['price'] ),
						'propertyState' => $propertyState,
						'operatingTime' => "$opHours:$opMinutes",
						'opTimeTS' => $operatingTime 
				);
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
	private function loadFarms() {
		foreach ( $this->xml ['farms'] as $farm ) {
			$farmId = intval ( $farm ['farmId'] );
			$this->farms [$farmId] = array (
					'name' => strval ( $farm ['name'] ),
					'color' => intval ( $farm ['color'] ),
					'loan' => intval ( $farm ['loan'] ),
					'money' => floatval ( $farm ['money'] ),
					'players' => array (),
					'contractFrom' => array (),
					'contractWith' => array () 
			);
			if (isset ( $farm->players )) {
				foreach ( $farm->players->player as $player ) {
					$this->farms [$farmId] ['players'] [] = array (
							'name' => strval ( $player ['lastNickname'] ),
							'isFarmManager' => get_bool ( $player ['farmManager'] ) 
					);
				}
			}
			if (isset ( $farm->contracting )) {
				foreach ( $farm->contracting->farm as $farm ) {
					$this->farms [$farmId] ['contractFrom'] [intval ( $farm ['farmId'] )] = true;
				}
			}
		}
		foreach ( $this->farms as $farmId1 => $farm ) {
			foreach ( $farm ['contractFrom'] as $farmId2 => $bool ) {
				$this->farms [$farmId2] ['contractWith'] [$farmId1] = true;
			}
		}
	}
	private function loadMissions() {
		foreach ( $this->xml ['missions'] as $mission ) {
			$missionData = array (
					'type' => sprintf ( '##MIS_%s##', strtoupper ( strval ( $mission ['type'] ) ) ),
					'reward' => intval ( $mission ['reward'] ),
					'status' => floatval ( $mission ['status'] ),
					'success' => get_bool ( $mission ['success'] ) 
			);
			if (isset ( $mission ['farmId'] )) {
				$missionData += array (
						'farmId' => intval ( $mission ['farmId'] ) 
				);
			}
			foreach ( $mission as $details ) {
				if ($details->getName () == 'field') {
					$vehicleUseCost = intval ( $details ['vehicleUseCost'] );
					$missionData += array (
							'field' => intval ( $details ['id'] ),
							'fieldSize' => $vehicleUseCost / 320,
							'vehicleUseCost' => $vehicleUseCost,
							'fruitTypeName' => translate ( $details ['fruitTypeName'] ) 
					);
				}
				if ($details->getName () == 'bale') {
					$missionData ['fruitTypeName'] = translate ( $details ['fillTypeName'] );
				}
			}
			$this->missions [] = $missionData;
		}
	}
	private function loadGreatDemands() {
		foreach ( $this->xml ['economy']->greatDemands->greatDemand as $greatDemand ) {
			$stationId = strval ( $greatDemand ['itemId'] );
			$fillTypeName = strval ( $greatDemand ['fillTypeName'] );
			$demandMultiplier = floatval ( $greatDemand ['demandMultiplier'] );
			$isRunning = get_bool ( $greatDemand ['isRunning'] );
			$this->demandIsRunning = $this->demandIsRunning || $isRunning;
			$l_fillType = translate ( $fillTypeName );
			if (isset ( $this->greatDemands [$l_fillType] )) {
				$this->greatDemands [$l_fillType] ['locations'] += array (
						$stationId => array (
								'demandMultiplier' => $demandMultiplier,
								'isRunning' => $isRunning 
						) 
				);
			} else {
				$this->greatDemands [$l_fillType] = array (
						'i3dName' => $fillTypeName,
						'locations' => array (
								$stationId => array (
										'demandMultiplier' => $demandMultiplier,
										'isRunning' => $isRunning 
								) 
						) 
				);
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
				$conn_id = ftp_ssl_connect ( $this->ftp ['server'], $this->ftp ['port'], 1 );
			} else {
				$conn_id = ftp_connect ( $this->ftp ['server'], $this->ftp ['port'], 1 );
			}
			if (! $conn_id) {
				echo ("Verbindung fehlgeschlagen<br>\r\n");
			} else {
				if (! ftp_login ( $conn_id, $this->ftp ['user'], $this->ftp ['pass'] )) {
					echo ("Login fehlgeschlagen<br>\r\n");
				} else {
					if (! ftp_pasv ( $conn_id, true )) {
						echo ("Umschalten in passiven Modus fehlgeschlagen<br>\r\n");
					} else {
						if (! ftp_get ( $conn_id, $local_file, $server_file, FTP_ASCII )) {
							echo ("Download von '$server_file' fehlgeschlagen<br>\r\n");
						}
					}
				}
				ftp_close ( $conn_id );
			}
		}
	}
}