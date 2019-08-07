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
if (! defined ( 'IN_FS19WS' ) && ! defined ( 'IN_INSTALL' )) {
	exit ();
}

$smarty->assign ( 'maps', getMaps () );
$smarty->assign ( 'languages', getLanguages () );
$smarty->assign ( 'styles', $styles );

$error = $success = false;
$mode = GetParam ( 'mode', 'G', 'start' );
$smarty->assign ( 'mode', $mode );
if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
	$submit = GetParam ( 'submit' );
	if ($submit == 'language') {
		$_SESSION ['language'] = $options ['general'] ['language'] = GetParam ( 'language' );
		setcookie ( 'fs19webstats', json_encode ( $options ), time () + 31536000 );
	} elseif ($submit == 'style') {
		$style = $options ['general'] ['style'] = GetParam ( 'style', 'P', 'fs19webstats' );
		setcookie ( 'fs19webstats', json_encode ( $options ), time () + 31536000 );
	} elseif ($submit == 'gameSettings') {
		$config = array (
				'adminPass' => GetParam ( 'adminpass1', 'P', '' ),
				'map' => GetParam ( 'map', 'P' ) 
		);
		$repeatedPassword = GetParam ( 'adminpass2', 'P', '' );
		switch ($mode) {
			case 'api' :
				$config += array (
						'type' => 'api',
						'serverIp' => GetParam ( 'serverip', 'P' ),
						'serverPort' => intval ( GetParam ( 'serverport', 'P' ) ),
						'serverCode' => GetParam ( 'servercode', 'P', '' ) 
				);
				if (filter_var ( $config ['serverIp'], FILTER_VALIDATE_IP ) === false) {
					$error .= '<div class="alert alert-danger"><strong>##ERROR##</strong> ##ERROR_IP##</div>';
				}
				if ($config ['serverPort'] < 1 || $config ['serverPort'] > 65536) {
					$error .= '<div class="alert alert-danger"><strong>##ERROR##</strong> ##ERROR_PORT##</div>';
				}
				if (strlen ( $config ['serverCode'] ) < 1) {
					$error .= '<div class="alert alert-danger"><strong>##ERROR##</strong> ##ERROR_CODE##</div>';
				}
				if (! $error) {
					if (! checkConnectionAPI ( $config ['serverIp'], $config ['serverPort'], $config ['serverCode'] )) {
						$error .= '<div class="alert alert-danger"><strong>##ERROR##</strong> ##ERROR_CODE##</div>';
					}
				}
				break;
			case 'ftp' :
				$config += array (
						'type' => 'ftp',
						'server' => GetParam ( 'ftpserver', 'P' ),
						'port' => intval ( GetParam ( 'ftpport', 'P' ) ),
						'ssl' => get_bool ( GetParam ( 'ftpssl', 'P', false ) ),
						'isgportal' => get_bool ( GetParam ( 'ftpgportal', 'P', false ) ),
						'path' => GetParam ( 'ftppath', 'P' ),
						'user' => GetParam ( 'ftpuser', 'P' ),
						'pass' => GetParam ( 'ftppass', 'P' ) 
				);
				if ($config ['ssl']) {
					$ftp_conn = ftp_ssl_connect ( $config ['server'], $config ['port'], 10 );
				} else {
					$ftp_conn = ftp_connect ( $config ['server'], $config ['port'], 10 );
				}
				if ($ftp_conn) {
					if (@ftp_login ( $ftp_conn, $config ['user'], $config ['pass'] )) {
						ftp_pasv ( $ftp_conn, true );
						if (! ftp_directory_exists ( $ftp_conn, $config ['path'] )) {
							$error .= '<div class="alert alert-danger"><strong>##ERROR##</strong> ##ERROR_FTPPATH##</div>';
						}
					} else {
						$error .= '<div class="alert alert-danger"><strong>##ERROR##</strong> ##ERROR_FTPUSERPASS##</div>';
					}
					ftp_close ( $ftp_conn );
				} else {
					$error .= '<div class="alert alert-danger"><strong>##ERROR##</strong> ##ERROR_FTPSERVER##</div>';
				}
				break;
			case 'web' :
				$weburl = pathinfo ( GetParam ( 'weburl', 'P' ) );
				$config += array (
						'type' => 'web',
						'url' => $weburl ['dirname'] . '/',
						'slot' => GetParam ( 'webslot', 'P' ),
						'user' => GetParam ( 'webuser', 'P' ),
						'pass' => GetParam ( 'webpass', 'P' ) 
				);
				break;
			case 'local' :
				$config += array (
						'type' => 'local',
						'path' => GetParam ( 'savepath', 'P', '' ) . DIRECTORY_SEPARATOR 
				);
				if (! file_exists ( $config ['path'] . 'careerSavegame.xml' )) {
					$error .= '<div class="alert alert-danger"><strong>##ERROR##</strong> ##ERROR_SAVEGAME##</div>';
				}
				break;
		}
		if (! file_exists ( "./config/maps/" . $config ['map'] )) {
			$error .= '<div class="alert alert-danger"><strong>##ERROR##</strong> ##ERROR_MAP##</div>';
		}
		if ($config ['adminPass'] != $repeatedPassword) {
			$error .= '<div class="alert alert-danger"><strong>##ERROR##</strong> ##PASSWORD_MATCH##</div>';
		}
		if (strlen ( $config ['adminPass'] ) < 6) {
			$error .= '<div class="alert alert-danger"><strong>##ERROR##</strong> ##PASSWORD_SHORT##</div>';
		}
		if (! $error) {
			$config ['adminPass'] = password_hash ( $config ['adminPass'], PASSWORD_DEFAULT );
			writeConfig2XML ( './config/webStatsConfig.xml', $config );
			$success = true;
		}
	}
}
$smarty->setTemplateDir ( "./styles/$style/templates" );
$smarty->assign ( 'style', $style );
$smarty->assign ( 'fsockopen', function_exists ( 'fsockopen' ) );
$smarty->assign ( 'fgetcontent', function_exists ( 'file_get_contents' ) );
$smarty->assign ( 'postdata', isset ( $config ) ? $config : array () );
$smarty->assign ( 'error', $error );
$smarty->assign ( 'success', $success );
$tpl_source = $smarty->fetch ( 'install.tpl' );
echo preg_replace_callback ( '/##(.+?)##/', 'prefilter_i18n', $tpl_source );
function checkConnectionAPI($serverIp, $serverPort, $serverCode) {
	error_reporting ( E_NOTICE );
	$fp = fsockopen ( $serverIp, $serverPort, $errno, $errstr, 4 );
	error_reporting ( E_ALL );
	if ($fp) {
		$out = "GET /feed/dedicated-server-stats.xml?code=" . $serverCode . " HTTP/1.0\r\n";
		$out .= "Host: " . $serverIp . "\r\n";
		$out .= "Connection: Close\r\n\r\n";
		fwrite ( $fp, $out );
		$resp = "";
		while ( ! feof ( $fp ) ) {
			$resp .= fgets ( $fp, 256 );
		}
		fclose ( $fp );
		if (preg_match ( "/HTTP\/1\.\d\s(\d+)/", $resp, $matches ) && $matches [1] == 200) {
			return true;
		}
	}
	return false;
}
function ftp_directory_exists($ftp, $dir) {
	// Function by swiftyexpress http://php.net/manual/de/function.ftp-chdir.php#87256
	// Get the current working directory
	@$origin = ftp_pwd ( $ftp );
	// Attempt to change directory, suppress errors
	if (@ftp_chdir ( $ftp, $dir )) {
		// If the directory exists, set back to origin
		@ftp_chdir ( $ftp, $origin );
		return true;
	}
	// Directory does not exist
	return false;
} 
