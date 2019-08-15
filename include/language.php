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

if (isset ( $_SERVER ['HTTP_ACCEPT_LANGUAGE'] )) {
	$languageFromBrowser = substr ( $_SERVER ['HTTP_ACCEPT_LANGUAGE'], 0, 2 );
} else {
	$languageFromBrowser = 'en';
}
if (file_exists ( './language/' . $languageFromBrowser . '/global.lng' )) {
	$defaultLanguage = $languageFromBrowser;
} else {
	$defaultLanguage = 'en'; // if you change the default language make sure the language file exists
}

if (! isset ( $_SESSION ['language'] )) {
	$_SESSION ['language'] = $defaultLanguage;
}

$creatorLanguageFile = getLanguageFileCreator ( $_SESSION ['language'] );
function getLanguageFileCreator($language) {
	$languages = getLanguages ();
	return $languages [$language] ['fileInfo'];
}
function getLangFile($language) {
	$langArray = array ();
	if (file_exists ( './language/' . $language . '/global.lng' )) {
		$entries = file ( './language/' . $language . '/global.lng' );
		foreach ( $entries as $row ) {
			if (substr ( ltrim ( $row ), 0, 2 ) == '//' || trim ( $row ) == '') { // ignore comments and emtpty rows
				continue;
			}
			$keyValuePair = explode ( '=', $row );
			$key = trim ( $keyValuePair [0] );
			$value = $keyValuePair [1];
			if (! empty ( $key )) {
				$langArray [$key] = chop ( $value );
			}
		}
	}
	return $langArray;
}
function getLanguages() {
	$languages = array ();
	$langDir = dir ( 'language' );
	while ( ($entry = $langDir->read ()) != false ) {
		if ($entry != "." && $entry != ".." && is_dir ( './language/' . $entry )) {
			if (file_exists ( './language/' . $entry . '/language.txt' )) {
				$langFile = file ( './language/' . $entry . '/language.txt' );
				$languages [$entry] = array (
						'path' => $entry,
						'englishName' => trim ( $langFile [0] ),
						'localName' => trim ( $langFile [1] ),
						'fileInfo' => trim ( $langFile [2] ) 
				);
			}
		}
	}
	$langDir->close ();
	ksort ( $languages );
	return $languages;
}
function prefilter_i18n($key) {
	$translations = getLangFile ( $_SESSION ['language'] );
	if (isset ( $translations [$key [1]] )) {
		return $translations [$key [1]];
	}
	return $key [0];
}


