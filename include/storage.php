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

if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
	$options ['storage'] ['sortByName'] = filter_var ( GetParam ( 'sortByName', 'P', 1 ), FILTER_VALIDATE_BOOLEAN );
	$options ['storage'] ['hideZero'] = filter_var ( GetParam ( 'hideZero', 'P', 1 ), FILTER_VALIDATE_BOOLEAN );
	$options ['storage'] ['showVehicles'] = filter_var ( GetParam ( 'showVehicles', 'P', 1 ), FILTER_VALIDATE_BOOLEAN );
	$options ['storage'] ['onlyPallets'] = filter_var ( GetParam ( 'onlyPallets', 'P', 1 ), FILTER_VALIDATE_BOOLEAN );
	$options ['storage'] ['3column'] = filter_var ( GetParam ( '3column', 'P', 1 ), FILTER_VALIDATE_BOOLEAN );
	$options ['storage'] ['hideAnimalsInStorage'] = filter_var ( GetParam ( 'hideAnimalsInStorage', 'P', 0 ), FILTER_VALIDATE_BOOLEAN );
	$options ['version'] = $cookieVersion;
	setcookie ( 'fs19webstats', json_encode ( $options ), time () + 31536000 );
}
$commodities = $savegame->commodities;
foreach ( $commodities as $name => $commodity ) {
	if ($commodity ['isCombine']) {
		unset ( $commodities [$name] );
		continue;
	}
	foreach ( $commodity ['locations'] as $location => $locationData ) {
		if ($options ['storage'] ['onlyPallets'] && ($location != 'Palettenlager' && ! isset ( $locationData ['FillablePallet'] ))) {
			$commodities [$name] ['overall'] -= $locationData ['fillLevel'];
			unset ( $commodities [$name] ['locations'] [$location] );
		} elseif (! $options ['storage'] ['showVehicles'] && isset ( $locationData ['isVehicle'] )) {
			$commodities [$name] ['overall'] -= $locationData ['fillLevel'];
			unset ( $commodities [$name] ['locations'] [$location] );
		} elseif ($options ['storage'] ['hideAnimalsInStorage'] && isset ( $locationData ['animal'] )) {
			$commodities [$name] ['overall'] -= $locationData ['fillLevel'];
			unset ( $commodities [$name] ['locations'] [$location] );
		}
		if ($options ['storage'] ['hideZero'] && $locationData ['fillLevel'] == 0) {
			unset ( $commodities [$name] ['locations'] [$location] );
		}
	}
	if ($options ['storage'] ['hideZero'] && $commodities [$name] ['overall'] == 0) {
		unset ( $commodities [$name] );
	}
	if (isset ( $commodities [$name] ) && sizeof ( $commodities [$name] ['locations'] ) == 0) {
		unset ( $commodities [$name] );
	}
}

ksort ( $commodities, SORT_LOCALE_STRING );

if (! $options ['storage'] ['sortByName']) {
	$sortFillLevel = array ();
	foreach ( $commodities as $name => $commodity ) {
		$sortFillLevel [] = $commodity ['overall'];
	}
	array_multisort ( $sortFillLevel, SORT_DESC, $commodities );
}

$smarty->assign ( 'commodities', $commodities );
$smarty->assign ( 'plants', array ()/*$plants*/ );
$smarty->assign ( 'outOfMap', $savegame->outOfMap );
$smarty->assign ( 'options', $options ['storage'] );
