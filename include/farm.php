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

include ('./include/savegame/Farm.class.php');
Farm::extractXML ( $savegame->getXML ( 'farms' ) );
$farms = Farm::getAllFarms ();

if (isset ( $_GET ['join_farm'] )) {
	$farmId = GetParam ( 'join_farm', 'G' );
	if (isset ( $farms [$farmId] )) {
		$options ['general'] ['farmId'] = $farmId;
		setcookie ( 'fs19webstats', json_encode ( $options ), time () + 31536000 );
		$nav->updateItems ( true );
	}
}
if (isset ( $_GET ['leave_farm'] )) {
	$farmId = GetParam ( 'leave_farm', 'G' );
	if (isset ( $farms [$farmId] )) {
		$options ['general'] ['farmId'] = 0;
		setcookie ( 'fs19webstats', json_encode ( $options ), time () + 31536000 );
		$nav->updateItems ( false );
	}
}

$smarty->assign ( 'selectedFarm', $options ['general'] ['farmId'] );
$smarty->assign ( 'farms', $farms );
