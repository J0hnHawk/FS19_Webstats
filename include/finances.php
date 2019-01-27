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
Farm::extractXML ( $savegame::$xml );

$mode = GetParam ( 'subPage', 'G', 'balance' );
switch ($mode) {
	case 'ratios' :
	case 'balance' :
		include ('./include/savegame/Prices.class.php');
		Price::extractXML ( $savegame::$xml );
		include ('./include/savegame/Commodities.class.php');
		Commodity::loadCommodities ( $savegame::$xml );
		include ('./include/savegame/Vehicles.class.php');
		Vehicle::extractXML ( $savegame::$xml, $options ['general'] ['farmId'], $mapconfig ['pallets'] );
		/*
		 * *** ASSETS
		 */
		$money = Farm::getMoney ( $_SESSION ['farmId'] );
		$assets = array (
				'A1' => 0,
				'A2' => Vehicle::getBuildingsResaleSum (),
				'A3' => Vehicle::getVehiclesResaleSum (),
				'B1' => 0,
				'B2' => 0,
				'B3' => 0,
				'B4' => 0,
				'B5' => 0,
				'CI1' => 0,
				'CI2' => 0,
				'CI3' => 0,
				'CII' => getBGAreceivable ( $savegame::$xml ),
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
		/*
		 * Betriebswirtschaftliche Kennzahlen
		 *
		 * Werte:
		 */
		$av = $assets ['A1'] + $assets ['A2'] + $assets ['A3']; // Anlagevermögen
		$aAv = $assets ['A2'] + $assets ['A3']; // abnutzbares Anlagevermögen
		$tv = $assets ['B1'] + $assets ['B2'] + $assets ['B3'] + $assets ['B4'] + $assets ['B5']; // Tiervermögen
		$v = $assets ['CI1'] + $assets ['CI3']; // Vorräte
		$kFo = $assets ['CII']; // kurzfristigeForderungen
		$fM = $assets ['CIII']; // fluessige Mittel
		$uv = $v + $kFo + $fM; // Umlaufvermögen
		$gk = $balanceSheetSum; // Gesamtkapital
		$ek = $liabilities ['A1'] + $liabilities ['A2']; // Eigenkapital
		$fkz = 0; // Fremdkapitalzinsen
		$g = $liabilities ['A2']; // Gewinn
		$fk = $liabilities ['B1'] + $liabilities ['B2']; // Fremdkapital
		$kFk = $liabilities ['B1'] + $liabilities ['B2']; // kurzfristige Forderungen
		/*
		 * Formeln:
		 */
		$ekq = floor ( 100 * $ek / $gk ); // Eigenkapitalquote
		$ekr = floor ( 100 * $g / $ek ); // Eigenkapitalrentabilität
		$adg = ($av == 0) ? NAN : floor ( 100 * $ek / $av ); // Anlagendeckungsgrad
		$fkq = floor ( 100 * $fk / $gk ); // Fremdkapitalquote
		$l3g = ($kFk == 0) ? NAN : floor ( 100 * ($uv) / $kFk ); // Liquidität 3. Grades
		$gkr = floor ( 100 * ($g + $fkz) / $ek ); // Gesamtkapitalrentabilität
		$fkd = ($kFk == 0) ? NAN : floor ( 100 * ($aAv + $tv + $uv) / $fk ); // Fremdkapitaldeckung
		$ratios = compact ( 'ekq', 'ekr', 'adg', 'fkq', 'l3g', 'gkr', 'fkd' );
		$smarty->assign ( 'ratios', $ratios );
		break;
	case '5dayhistory' :
		$financeElements = array (
				'newVehiclesCost' => 0,
				'soldVehicles' => 0,
				'newAnimalsCost' => 1,
				'soldAnimals' => 1,
				'constructionCost' => 2,
				'soldBuildings' => 2,
				'fieldPurchase' => 2,
				'fieldSelling' => 2,
				'vehicleRunningCost' => 3,
				'vehicleLeasingCost' => 3,
				'animalUpkeep' => 1,
				'propertyMaintenance' => 4,
				'propertyIncome' => 4,
				'soldWood' => 6,
				'soldBales' => 6,
				'soldWool' => 1,
				'soldMilk' => 1,
				'purchaseFuel' => 0,
				'purchaseSeeds' => 8,
				'purchaseFertilizer' => 8,
				'purchaseSaplings' => 8,
				'purchaseWater' => 8,
				'harvestIncome' => 6,
				'incomeBga' => 6,
				'missionIncome' => 7,
				'wagePayment' => 5,
				'other' => 8,
				'loanInterest' => 9 
		);
		$summary = array (
				'VEHICLES_NCS', // 0/ newVehiclesCost, soldVehicles, purchaseFuel
				'LIFESTOCK_NSU', // 1/ newAnimalsCost, soldAnimals, animalUpkeep, soldWool, soldMilk
				'PROPERTY_CSP', // 2/ constructionCost, soldBuildings, fieldPurchase
				'VEHICLES_U', // 3/ vehicleRunningCost, vehicleLeasingCost
				'PROPERTY_MI', // 4/ propertyMaintenance, propertyIncome
				'WAGE_PAYMENT', // 5/ wagePayment
				'HARVEST', // 6/ soldWood, soldBales, harvestIncome, incomeBga
				'MISSION', // 7/ missionIncome
				'OTHER', // 8/ purchaseSeeds, purchaseFertilizer, purchaseSaplings, purchaseWater, other
				'LOANINTEREST' // 9/ loanInterest
		);
		$weekdays = array (
				'SUNDAY',
				'MONDAY',
				'TUESDAY',
				'WEDNESDAY',
				'THURSDAY',
				'FRIDAY',
				'SATURDAY',
				'SUNDAY' 
		);
		$smarty->assign ( 'weekdays', $weekdays );
		
		// $operatingResult = array_sum ( $financeSummary );
		$financeHistory = Farm::getAllFarms () [$_SESSION ['farmId']] ['finances'];
		$smarty->assign ( 'financeHistory', $financeHistory );
		$smarty->assign ( 'financeElements', $financeElements );
		$smarty->assign ( 'money', Farm::getAllFarms () [$_SESSION ['farmId']] ['money'] );
		$smarty->assign ( 'loan', Farm::getAllFarms () [$_SESSION ['farmId']] ['loan'] );
		
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
function getBGAreceivable($xml) {
	$receivables = 0;
	foreach ( $xml ['items'] as $item ) {
		if ($item ['className'] != 'BgaPlaceable' || $item ['farmId'] != $_SESSION ['farmId'] || strval ( $item ['className'] ) == 'Bale') {
			continue;
		}
		$receivables += intval ( $item->bga ['money'] );
	}
	return $receivables;
}