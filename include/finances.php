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
$mode = GetParam ( 'subPage', 'G', 'balance' );
switch ($mode) {
	case 'balance' :
		include ('./include/savegame/Prices.class.php');
		Price::extractXML ( $savegame::$xml );
		include ('./include/savegame/Commodities.class.php');
		Commodity::loadCommodities ( $savegame::$xml );
		include ('./include/savegame/Vehicles.class.php');
		Vehicle::extractXML ( $savegame::$xml, $options ['general'] ['farmId'], $mapconfig ['pallets'] );
		include ('./include/savegame/Farm.class.php');
		Farm::extractXML ( $savegame::$xml );
		
		/*
		 * *** ASSETS
		 */
		$money = Farm::getMoney ( $_SESSION ['farmId'] );
		$assets = array (
				'A1' => 0,
				'A2' => Vehicle::getBuildingsResaleSum (),
				'A3' => Vehicle::getVehiclesResaleSum (),
				'CIII' => ($money < 0) ? 0 : $money 
		);
		foreach ( $savegame::$xml ['farmland'] as $farmland ) {
			if ($farmland ['farmId'] == $_SESSION ['farmId']) {
				$assets ['A1'] += $mapconfig ['Farmlands'] [intval ( $farmland ['id'] )] ['price'];
			}
		}
		$prices = Price::getAllPrices ();
		foreach ( Commodity::getAllCommodities () as $l_fillType => $commodity ) {
			$fillType = $commodity ['i3dName'];
			if ($fillType == 'CHAFF') {
				// Chaff will be silage after some time in a silo
				$l_fillType = translate ( 'SILAGE' );
			}
			if (isset ( $prices [$l_fillType] )) {
				$pricePerLiter = $prices [$l_fillType] ['bestPrice'] / 1000;
			} elseif (isset ( $mapconfig ['fillTypes'] [$fillType] ['pricePerLiter'] )) {
				$pricePerLiter = $mapconfig ['fillTypes'] [$fillType] ['pricePerLiter'];
			} else {
				$pricePerLiter = 0;
				echo ("Kein Preis für $l_fillType ($fillType)<br>");
			}
			if (! isset ( $mapconfig ['fillTypes'] [$fillType] ['balanceSheet'] )) {
				$assetPosition = 'CI3';
			} else {
				$assetPosition = $mapconfig ['fillTypes'] [$fillType] ['balanceSheet'];
			}
			$assets = addValue ( $assets, $assetPosition, floor ( $commodity ['overall'] * $pricePerLiter ) );
		}
		/*
		 * LIABILITIES
		 */
		$liabilities = array (
				'A1' => 500000,
				'B1' => Farm::getLoan ( $_SESSION ['farmId'] ),
				'B2' => (($money < 0) ? $money : 0) * - 1 
		);
		$balanceSheetSum = array_sum ( $assets );
		$liabilities ['A2'] = $balanceSheetSum - ($liabilities ['A1'] + $liabilities ['B1'] + $liabilities ['B2']);
		$smarty->assign ( 'assets', $assets );
		$smarty->assign ( 'liabilities', $liabilities );
		$smarty->assign ( 'balanceSheetSum', $balanceSheetSum );
		$smarty->assign ( 'farmName', Farm::getName ( $_SESSION ['farmId'] ) );
		break;
	case '5dayhistory' :
		break;
	case 'summary' :
		break;
}
function addValue($array, $key, $value) {
	if (! isset ( $array [$key] )) {
		$array [$key] = 0;
	}
	$array [$key] += $value;
	return $array;
}