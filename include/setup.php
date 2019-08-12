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
$maps = getMaps ();
$smarty->assign ( 'maps', $maps );

$config = $error = array (
		'webStatsVersion' => false,
		'savegame_type' => false,
		'fs19path' => false,
		'link_xml' => false,
		'webinterface_url' => false,
		'webinterface_username' => false,
		'webinterface_password' => false,
		'savegame_slot' => false,
		'cacheTimeout' => false,
		'map' => false,
		'webstatsPassword' => false,
		'webstatsPasswordRepeat' => false 
);

$success = false;
$mode = GetParam ( 'mode', 'G', 'language' );
if ($mode == 'language') {
	$languages = getLanguages ();
	$smarty->assign ( 'languages', $languages );
	$language = GetParam ( 'selected', 'G', '' );
	if (isset ( $languages [$language] )) {
		$_SESSION ['language'] = $options ['general'] ['language'] = $language;
		setcookie ( 'fs19webstats', json_encode ( $options ), time () + 31536000 );
		$mode = 'savegame_type';
	}
}
$smarty->assign ( 'mode', $mode );
if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
	foreach ( $config as $varName => $value ) {
		$config [$varName] = GetParam ( $varName );
		$error [$varName] = false;
	}
	switch ($mode) {
		case 'server' :
			$config ['savegame_type'] = $mode;
			$config ['cacheTimeout'] = 60; // Seconds
			if (! check_link_xml ( $config ['link_xml'] )) {
				$error ['link_xml'] = true;
			}
			$error = array_merge ( $error, check_webinterface ( $config ) );
			break;
		case 'local' :
			$config ['savegame_type'] = $mode;
			if (! file_exists ( $config ['fs19path'] )) {
				$error ['fs19path'] = true;
			} else {
				if (substr ( $config ['fs19path'], - 1 ) != DIRECTORY_SEPARATOR) {
					$config ['fs19path'] .= DIRECTORY_SEPARATOR;
				}
				$fileToCheck = $config ['fs19path'] . $config ['savegame_slot'] . DIRECTORY_SEPARATOR . 'careerSavegame.xml';
				if (! file_exists ( $fileToCheck )) {
					$error ['savegame_slot'] = true;
				}
			}
			break;
		default :
			$error ['savegame_type'] = true;
	}
	if (! isset ( $maps [$config ['map']] )) {
		$error ['map'] = true;
	}
	if (strlen ( $config ['webstatsPassword'] ) < 6) {
		$error ['webstatsPassword'] = true;
	} elseif ($config ['webstatsPassword'] != $config ['webstatsPasswordRepeat']) {
		$error ['webstatsPasswordRepeat'] = true;
	}
	if (array_sum ( $error ) == 0) {
		$config ['webStatsVersion'] = $webStatsVersion;
		if ($mode == 'server') {
			$config ['webinterface_url'] = getWebinterfaceURL ( $config ['webinterface_url'] );
		}
		$config ['webstatsPassword'] = password_hash ( $config ['webstatsPassword'], PASSWORD_DEFAULT );
		unset ( $config ['webstatsPasswordRepeat'] );
		writeConfig2XML ( './config/webStatsConfig.xml', $config );
		$success = true;
	}
}
$smarty->setTemplateDir ( "./styles/$style/templates" );
$smarty->assign ( 'style', $style );
$smarty->assign ( 'fsockopen', function_exists ( 'fsockopen' ) );
$smarty->assign ( 'fgetcontent', function_exists ( 'file_get_contents' ) );
$smarty->assign ( 'config', $config );
$smarty->assign ( 'error', $error );
$smarty->assign ( 'success', $success );
$tpl_source = $smarty->fetch ( 'setup.tpl' );
echo preg_replace_callback ( '/##(.+?)##/', 'prefilter_i18n', $tpl_source );
function check_link_xml($url) {
	if (filter_var ( $url, FILTER_VALIDATE_URL )) {
		$ch = curl_init ( $url );
		curl_setopt_array ( $ch, array (
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_CONNECTTIMEOUT => 10 
		) );
		$response = curl_exec ( $ch );
		$http_code = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
		curl_close ( $ch );
		if ($http_code == 200) {
			return true;
		}
	}
	return false;
}
function getWebinterfaceURL($fullURL) {
	$webinterface_url_parts = pathinfo ( $fullURL );
	return $webinterface_url_parts ['dirname'] . '/';
}
function check_webinterface($config) {
	$error = array ();
	if (filter_var ( $config ['webinterface_url'], FILTER_VALIDATE_URL )) {
		$webinterface = getWebinterfaceURL ( $config ['webinterface_url'] );
		$ch = curl_init ();
		curl_setopt_array ( $ch, array (
				CURLOPT_URL => $webinterface . 'index.html',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_CONNECTTIMEOUT => 10 
		) );
		$response = curl_exec ( $ch );
		$http_code = curl_getinfo ( $ch, CURLINFO_HTTP_CODE );
		if ($http_code == 200) {
			$postData = array (
					'username' => $config ['webinterface_username'],
					'password' => $config ['webinterface_password'],
					'login' => 'Anmelden' 
			);
			curl_setopt_array ( $ch, array (
					CURLOPT_URL => $webinterface . 'index.html',
					CURLOPT_POST => true,
					CURLOPT_POSTFIELDS => $postData,
					CURLOPT_COOKIEJAR => './cache/cookie.txt' 
			) );
			$response = curl_exec ( $ch );
			if (strpos ( $response, 'Username or password incorrect' ) !== false) {
				$error ['webinterface_username'] = true;
			} else {
				curl_setopt_array ( $ch, array (
						CURLOPT_URL => $webinterface . $config ['savegame_slot'],
						CURLOPT_POST => false 
				) );
				$response = curl_exec ( $ch );
				if (strlen ( $response ) < 100) {
					$error ['savegame_slot'] = true;
				}
				curl_setopt ( $ch, CURLOPT_URL, $webinterface . 'index.html?logout=true' );
				$store = curl_exec ( $ch );
			}
		} else {
			$error ['webinterface_url'] = true;
		}
		curl_close ( $ch );
	} else {
		$error ['webinterface_url'] = true;
	}
	return $error;
}
