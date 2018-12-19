<?php
/**
 *
 * This file is part of the "FS19 Web Stats" package.
 * Copyright (C) 2017-2018 John Hawk <john.hawk@gmx.net>
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
						'showInNav' => false,//true,
						'active' => false,
						'text' => '##OVERVIEW##' 
				),
				'storage' => array (
						'showInNav' => false,//$showInNav,
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
						'showInNav' => false,//$showInNav,
						'active' => false,
						'text' => '##FINANCES##' 
				),
				'husbandry' => array (
						'showInNav' => false,//$showInNav,
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
						'showInNav' => false,//$showInNav,
						'active' => false,
						'text' => '##STATISTICS##' 
				),
				'production' => array (
						'showInNav' => false,//$showInNav,
						'active' => false,
						'text' => '##PRODUCTION##' 
				),
				'commodity' => array (
						'showInNav' => false,//$showInNav,
						'active' => false,
						'text' => '##COMMODITY##' 
				),
				'options' => array (
						'showInNav' => false,//$showInNav,
						'active' => false,
						'text' => '##OPTIONS##' 
				),
				'lizenz' => array (
						'showInNav' => false,
						'active' => false,
						'text' => '##LIZENZ##' 
				),
				'factories' => array (
						'showInNav' => false,//$showInNav,
						'active' => false,
						'text' => '##FACTORIES##' 
				) 
		);
	}
}