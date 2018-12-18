<?php
/**
 * This file is part of the "FS19 Web Stats" package.
 * Copyright (C) 2018 John Hawk <john.hawk@gmx.net>
 *
 * "FS19 Web Stats" is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * "FS19 Web Stats" is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */
ini_set ( 'error_reporting', E_ALL );
ini_set ( 'display_errors', 1 );
ini_set ( 'log_errors', 1 );
ini_set ( 'error_log', 'error.log' );

session_start ();
define ( 'IN_FS19WS', true );

// Change next lines if you are not german ;-)
if (function_exists ( 'date_default_timezone_set' )) {
	date_default_timezone_set ( 'Europe/Berlin' );
}

$defaultStyle = 'fs19webstats';

require ('./include/smarty/Smarty.class.php');
require ('./include/language.php');
require ('./include/functions.php');

// Load cookie with user settings
include ('./include/coockie.php');
$style = $options ['general'] ['style'];

$smarty = new Smarty ();
$smarty->debugging = false;
$smarty->caching = false;
$smarty->assign ( 'webStatsVersion', '2.0.0-0001 (29.11.2018)' );

include ('./include/loadConfig.php');
$smarty->assign ( 'onlineUser', sizeof ( $onlineUser ) );
$smarty->assign ( 'hideFooter', $options ['general'] ['hideFooter'] );

include ('./include/Savegame.class.php');
$savegame = new Savegame ( $config );
$smarty->assign ( 'currentDay', $savegame->currentDay () );
$smarty->assign ( 'dayTime', $savegame->dayTime () );

// require ('./include/savegame.php');
$serverOnline = true;

// Existing pages and nav items
$showInNav = ($options ['general'] ['farmId'] > 0) ? true : false;
$navItems = array (
		'overview' => array (
				'showInNav' => true,
				'active' => false,
				'text' => '##OVERVIEW##' 
		),
		'storage' => array (
				'showInNav' => $showInNav,
				'active' => false,
				'text' => '##STORAGE##' 
		),
		'prices' => array (
				'showInNav' => true,
				'active' => false,
				'text' => '##PRICES##' 
		),
		'vehicles' => array (
				'showInNav' => $showInNav,
				'active' => false,
				'text' => '##VEHICLES##' 
		),
		'finances' => array (
				'showInNav' => $showInNav,
				'active' => false,
				'text' => '##FINANCES##' 
		),
		'husbandry' => array (
				'showInNav' => $showInNav,
				'active' => false,
				'text' => '##HUSBANDRY##' 
		),
		'missions' => array (
				'showInNav' => true,
				'active' => false,
				'text' => '##MISSIONS##' 
		),
		'farm' => array (
				'showInNav' => true,
				'active' => false,
				'text' => '##FARM##' 
		),
		'statistics' => array (
				'showInNav' => $showInNav,
				'active' => false,
				'text' => '##STATISTICS##' 
		),
		'production' => array (
				'showInNav' => false,
				'active' => false,
				'text' => '##PRODUCTION##' 
		),
		'commodity' => array (
				'showInNav' => false,
				'active' => false,
				'text' => '##COMMODITY##' 
		),
		'options' => array (
				'showInNav' => false,
				'active' => false,
				'text' => '##OPTIONS##' 
		),
		'lizenz' => array (
				'showInNav' => false,
				'active' => false,
				'text' => '##LIZENZ##' 
		),
		'factories' => array (
				'showInNav' => false,
				'active' => false,
				'text' => '##FACTORIES##' 
		) 
);
$page = GetParam ( 'page', 'G' );
if (! isset ( $navItems [$page] )) {
	
	$page = 'prices';
}
$navItems [$page] ['active'] = true;
$smarty->assign ( 'page', $page );
if ($serverOnline) {
	include ("./include/$page.php");
}
$smarty->assign ( 'navItems', $navItems );
$smarty->assign ( 'reloadPage', $options ['general'] ['reload'] );
$smarty->assign ( 'serverOnline', $serverOnline );
$smarty->setTemplateDir ( "./styles/$style/templates" );
$smarty->assign ( 'style', $style );
$tpl_source = $smarty->fetch ( 'index.tpl', $style, $style );

echo preg_replace_callback ( '/##(.+?)##/', 'prefilter_i18n', $tpl_source );