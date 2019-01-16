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
		$prices = Price::getAllPrices ();
		include ('./include/savegame/Commodities.class.php');
		Commodity::loadCommodities ( $savegame::$xml );
		$commoditiesFullSale = 0;
		foreach ( Commodity::getAllCommodities () as $l_fillType => $commodity ) {
			$fillType = $commodity ['i3dName'];
			if ($fillType == 'CHAFF') {
				$l_fillType = translate ( 'SILAGE' );
			}
			if (isset ( $prices [$l_fillType] )) {
				$commoditiesFullSale += getCurrentValue ( $commodity ['overall'], $prices [$l_fillType] ['bestPrice'] );
			} elseif (isset ( $mapconfig ['fillTypes'] [$fillType] ['pricePerLiter'] )) {
				$commoditiesFullSale += getCurrentValue ( $commodity ['overall'], $mapconfig ['fillTypes'] [$fillType] ['pricePerLiter'], true );
			} else {
				echo ("Kein Preis für $l_fillType ($fillType)<br>");
			}
		}
		include ('./include/savegame/Vehicles.class.php');
		Vehicle::extractXML ( $savegame->getXML ( 'vehicles' ), $options ['general'] ['farmId'], $mapconfig ['pallets'] );
		$vehicleResameSum = Vehicle::getVehiclesResameSum ();
		break;
	case '5dayhistory' :
		break;
	case 'summary' :
		break;
}
var_dump ( $commoditiesFullSale, $vehicleResameSum );
function getCurrentValue($storage, $price, $pricePerLiter = false) {
	return floor ( $storage * $price / (($pricePerLiter) ? 1 : 1000) );
}
exit ();