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
	public $currentDay;
	public $dayTime;
	public $difficulty;
	public $economicDifficulty;
	public static $xml = array ();
	protected $xmlFiles = array (
			'environment.xml',
			'economy.xml',
			'farms.xml',
			'farmland.xml',
			'items.xml',
			'missions.xml',
			'vehicles.xml',
			'careerSavegame.xml' 
	);
	private $ftp = array ();
	private $cache = './cache/';
	public function __construct($webStatsConfig, $farmId = 1) {
		$this->test = new stdClass ();
		if (! file_exists ( $this->cache )) {
			mkdir ( $this->cache );
		}
		$this->farmId = $farmId;
		switch ($webStatsConfig->savegame->type) {
			case 'ftp' :
				$this->ftp ['server'] = $webStatsConfig->savegame->server;
				$this->ftp ['port'] = $webStatsConfig->savegame->port;
				$this->ftp ['ssl'] = get_bool ( $webStatsConfig->savegame->ssl );
				$this->ftp ['path'] = $webStatsConfig->savegame->path;
				$this->ftp ['user'] = $webStatsConfig->savegame->user;
				$this->ftp ['pass'] = $webStatsConfig->savegame->pass;
				$this->ftp ['isgportal'] = get_bool ( $webStatsConfig->savegame->gportal );
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
					$basename = basename ( $this->xmlFiles [$s1], '.xml' );
					self::$xml [$basename] = simplexml_load_file ( $this->cache . $this->xmlFiles [$s1] );
					$this->$basename = new stdClass ();
					$this->$basename = simplexml_load_file ( $this->cache . $this->xmlFiles [$s1] );
				}
				break;
			case 'local' :
				$this->cache = $webStatsConfig->savegame->path;
				for($s1 = 0; $s1 < sizeof ( $this->xmlFiles ); $s1 ++) {
					$basename = basename ( $this->xmlFiles [$s1], '.xml' );
					self::$xml [$basename] = simplexml_load_file ( $this->cache . $this->xmlFiles [$s1] );
					$this->$basename = new stdClass ();
					$this->$basename = simplexml_load_file ( $this->cache . $this->xmlFiles [$s1] );
				}
				break;
			case 'web' :
				$username = $webStatsConfig->savegame->user;
				$password = $webStatsConfig->savegame->pass;
				$url = $webStatsConfig->savegame->url;
				$loginUrl = $url . 'index.html?lang=de';
				$savegameUrl = $url . $webStatsConfig->savegame->slot;
				$logoutUrl = $url . 'index.html?logout=true&lang=de';
				$zipFile = $this->cache . 'savegame.zip';
				$cacheTimeout = 60;
				if (file_exists ( $zipFile ) && filemtime ( $zipFile ) > (time () - ($cacheTimeout) + rand ( 0, 10 ))) {
				} else {
					$ch = curl_init ();
					curl_setopt ( $ch, CURLOPT_URL, $loginUrl );
					curl_setopt ( $ch, CURLOPT_POST, 1 );
					curl_setopt ( $ch, CURLOPT_POSTFIELDS, "username=$username&password=$password&login=Anmelden" );
					curl_setopt ( $ch, CURLOPT_COOKIEJAR, 'cookie.txt' );
					curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
					$store = curl_exec ( $ch );
					curl_setopt ( $ch, CURLOPT_URL, $savegameUrl );
					$content = curl_exec ( $ch );
					file_put_contents ( $zipFile, $content );
					curl_setopt ( $ch, CURLOPT_URL, $logoutUrl );
					$store = curl_exec ( $ch );
					// curl_close ( $ch );
					if (class_exists ( 'ZipArchive' )) {
						$zip = new ZipArchive ();
						$extractPath = "./cache";
						if ($zip->open ( $zipFile ) != "true") {
							echo "Error :- Unable to open the Zip File";
						}
						$zip->extractTo ( $extractPath );
						$zip->close ();
					}
				}
				for($s1 = 0; $s1 < sizeof ( $this->xmlFiles ); $s1 ++) {
					$basename = basename ( $this->xmlFiles [$s1], '.xml' );
					self::$xml [$basename] = simplexml_load_file ( $this->cache . $this->xmlFiles [$s1] );
					$this->$basename = new stdClass ();
					$this->$basename = simplexml_load_file ( $this->cache . $this->xmlFiles [$s1] );
				}
				break;
			case 'api' :
				break;
		}
		$this->currentDay = intval ( self::$xml ['environment']->currentDay );
		$this->dayTime = gmdate ( "H:i", floatval ( self::$xml ['environment']->dayTime ) * 60 );
		$this->difficulty = intval ( self::$xml ['careerSavegame']->settings->difficulty );
		$this->economicDifficulty = intval ( self::$xml ['careerSavegame']->settings->economicDifficulty );
	}
	public function getCurrentDay() {
		return intval ( self::$xml ['environment']->currentDay );
	}
	public function getPriceMultiplier() {
		return self::PRICE_MULTIPLIER [$this->economicDifficulty];
	}
	public function getCostMultiplier() {
		return self::COST_MULTIPLIER [$this->economicDifficulty];
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
	private function getFileByFTP($file) {
		if ($this->ftp ['isgportal']) {
			$URL = "ftp://" . $this->ftp ['user'] . ":" . $this->ftp ['pass'] . "@" . $this->ftp ['server'] . ":" . $this->ftp ['port'] . $this->ftp ['path'];
			$fp = fopen ( $this->cache . $file . '.temp', "w" );
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
			$local_file = $this->cache . $file . '.temp';
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
		libxml_use_internal_errors ( true );
		$stat = simplexml_load_file ( $this->cache . $file . '.temp' );
		if ($stat !== false) {
			rename ( $this->cache . $file . '.temp', $this->cache . $file );
		} else {
			// The XML file propably could not be loaded correctly.
			echo ("Fehler beim Laden der Datei: $file<br>");
		}
		libxml_use_internal_errors ( false );
	}
}