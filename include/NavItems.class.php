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
class Nav {
	public $items;
	public function __construct($showInNav) {
		self::updateItems ( $showInNav );
	}
	public function setActiveItem($page) {
		$this->items [$page] ['active'] = true;
	}
	public function updateItems($showInNav) {
		$this->items = array (
				'overview' => array (
						'showInNav' => false, // true,
						'hasSubmenu' => false,
						'active' => false,
						'text' => '##OVERVIEW##' 
				),
				'storage' => array (
						'showInNav' => $showInNav,
						'hasSubmenu' => false,
						'active' => false,
						'text' => '##STORAGE##' 
				),
				'prices' => array (
						'showInNav' => true,
						'hasSubmenu' => true,
						'active' => false,
						'text' => '##PRICES##',
						'submenu' => array (
								'bestPrices' => array (
										'showInNav' => true,
										'active' => false,
										'text' => '##BESTPRICES##' 
								),
								'allPrices' => array (
										'showInNav' => true,
										'active' => false,
										'text' => '##ALLPRICES##' 
								) 
						) 
				),
				'vehicles' => array (
						'showInNav' => $showInNav,
						'hasSubmenu' => true,
						'active' => false,
						'text' => '##VEHICLES##',
						'submenu' => array (
								'vehicles' => array (
										'showInNav' => true,
										'active' => false,
										'text' => '##VEHICLES##' 
								),
								'buildings' => array (
										'showInNav' => true,
										'active' => false,
										'text' => '##BUILDINGS##' 
								) 
						) 
				),
				'finances' => array (
						'showInNav' => $showInNav,
						'hasSubmenu' => true,
						'active' => false,
						'text' => '##FINANCES##',
						'submenu' => array (
								'5dayhistory' => array (
										'showInNav' => true,
										'active' => false,
										'text' => '##FINANCES5DAYS##' 
								),
								'balance' => array (
										'showInNav' => true,
										'active' => false,
										'text' => '##BALANCESHEET##' 
								),
								'ratios' => array (
										'showInNav' => true,
										'active' => false,
										'text' => '##RATIOS##' 
								) 
						) 
				),
				'animals' => array (
						'showInNav' => $showInNav,
						'hasSubmenu' => false,
						'active' => false,
						'text' => '##ANIMALS##' 
				),
				'missions' => array (
						'showInNav' => true,
						'hasSubmenu' => false,
						'active' => false,
						'text' => '##MISSIONS##' 
				),
				'statistics' => array (
						'showInNav' => $showInNav,
						'hasSubmenu' => false,
						'active' => false,
						'text' => '##STATISTICS##'
				),
				'farms' => array (
						'showInNav' => true,
						'hasSubmenu' => false,
						'active' => false,
						'text' => '##FARMS##' 
				),
				'production' => array (
						'showInNav' => false, // $showInNav,
						'hasSubmenu' => false,
						'active' => false,
						'text' => '##PRODUCTION##' 
				),
				'commodity' => array (
						'showInNav' => false, // $showInNav,
						'hasSubmenu' => false,
						'active' => false,
						'text' => '##COMMODITY##' 
				),
				'options' => array (
						'showInNav' => true, // $showInNav,
						'hasSubmenu' => false,
						'active' => false,
						'text' => '##SETTINGS##' 
				),
				'info' => array (
						'showInNav' => true,
						'hasSubmenu' => false,
						'active' => false,
						'text' => '##INFO##' 
				),
				'factories' => array (
						'showInNav' => false, // $showInNav,
						'hasSubmenu' => false,
						'active' => false,
						'text' => '##FACTORIES##' 
				) 
		);
	}
}