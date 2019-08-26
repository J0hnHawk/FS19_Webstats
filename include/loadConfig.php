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

// load Styles
$styles = array ();
$stylesDir = dir ( 'styles' );
while ( ($entry = $stylesDir->read ()) != false ) {
	if ($entry != "." && $entry != ".." && is_dir ( './styles/' . $entry )) {
		if (file_exists ( './styles/' . $entry . '/style.cfg' )) {
			$styleFile = file ( './styles/' . $entry . '/style.cfg' );
			$keyValuePair = explode ( '=', $styleFile [0] );
			$styles [$entry] = array (
					'path' => $entry,
					'name' => trim ( $keyValuePair [1] )
			);
		}
	}
}
$stylesDir->close ();

// Load server configuration - start install if it does not exists
$startSetup = true;
$configFile = './config/webStatsConfig.xml';
if (file_exists ( $configFile )) {
	$webStatsConfig = simplexml_load_file ( $configFile );
	if (! isset ( $webStatsConfig->webStatsVersion )) {
		// Changed config file again
		unlink ( $configFile );
	} else {
		$startSetup = false;
	}
}
// Delete of configuration
$oldConfigFile = './config/server.conf';
if (file_exists ( $oldConfigFile )) {
	unlink ( $oldConfigFile );
}
// End of delete old configuration
if ($startSetup) {
	define ( 'IN_INSTALL', true );
	include ('./include/setup.php');
	exit ();
}

// Load map infomations
$map = loadMapCFGfile ( $webStatsConfig->map );
$smarty->assign ( 'map', $map );

$userLang = $_SESSION ['language'];
// Kartenkonfiguration aus XML Dateien laden
$gameData = loadSavegameSpefics ( 'basics', $userLang );
$gameData = loadSavegameSpefics ( 'maps' . DIRECTORY_SEPARATOR . $webStatsConfig->map, $userLang, $gameData );
$gameData = loadSavegameSpefics ( 'mods', $userLang, $gameData );

// count active user
$userFile = './config/onlineUser.conf';
$onlineUser = array ();
if (file_exists ( $userFile )) {
	$user = file ( $userFile );
	foreach ( $user as $row ) {
		list ( $id, $time ) = explode ( '||', trim ( $row ) );
		if ($time + 300 > time ()) {
			$onlineUser [$id] = $time;
		}
	}
}
$onlineUser [$_SERVER ["REMOTE_ADDR"]] = time ();
$fp = fopen ( $userFile, 'w' );
foreach ( $onlineUser as $id => $time ) {
	fwrite ( $fp, "$id||$time\r\n" );
}
fclose ( $fp );