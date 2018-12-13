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
	private $xmlFiles = array (
			'environment.xml',
			'economy.xml',
			'items.xml' 
	);
	private $cache = './cache';
	private $ftpURL = '';
	private $xml = array ();
	public function __construct($config) {
		if (! file_exists ( $this->cache )) {
			mkdir ( $this->cache );
		}
		switch ($config ['type']) {
			case 'ftp' :
				$this->ftpURL = "ftp://" . $config ['user'] . ":" . $config ['pass'] . "@" . $config ['server'] . ":" . $config ['port'] . $config ['path'];
				$updateFiles = true;
				if (file_exists ( $this->cache . $this->xmlFiles [0] )) {
					$careerEnvironment = simplexml_load_file ( $this->cache . $this->xmlFiles [0] );
					$lastDayTime = intval ( $careerEnvironment->currentDay ) * 86400 + intval ( $careerEnvironment->dayTime * 60 );
					self::getFileByFTP ( $this->xmlFiles [0] );
					$newDayTime = intval ( $careerEnvironment->currentDay ) * 86400 + intval ( $careerEnvironment->dayTime * 60 );
					if ($newDayTime == $lastDayTime) {
						$updateFiles = false;
					}
				}
				if ($updateFiles) {
					foreach ( $this->xmlFiles as $xmlFile ) {
						self::getFileByFTP ( $xmlFile );
					}
				}
				foreach ( $this->xmlFiles as $xmlFile ) {
					$this->xml [basename ( $xmlFile, '.xml' )] = simplexml_load_file ( $this->cache . $xmlFile );
				}
				break;
			case 'local' :
				$this->cache = $config ['path'];
				break;
			case 'api' :
				break;
		}
	}
	public function currentDay() {
		return intval ( $this->xml ['environment']->currentDay );
	}
	public function dayTime() {
		return gmdate ( "H:i", intval ( $this->xml ['environment']->dayTime * 60 ) );
	}
	private function getFileByFTP($file) {
		$fp = fopen ( $this->cache . $file, "w" );
		$url = $this->ftpURL . $file;
		$curl = curl_init ();
		curl_setopt ( $curl, CURLOPT_URL, $url );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 0 );
		curl_setopt ( $curl, CURLOPT_UPLOAD, 0 );
		curl_setopt ( $curl, CURLOPT_FILE, $fp );
		$result = curl_exec ( $curl );
		curl_close ( $curl );
		fclose ( $fp );
	}
}