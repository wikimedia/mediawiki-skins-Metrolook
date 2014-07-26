<?php
/**
 * Vector - Modern version of MonoBook with fresh look and many usability
 * improvements.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup Skins
 */

$wgExtensionCredits['skin'][] = array(
	'path' => __FILE__,
	'name' => 'Metrolook',
	'description' => 'Metrolook skin for MediaWiki.',
	'version' => '1.0.4',
	'url' => 'https://www.mediawiki.org/wiki/Skin:Metrolook',
	'author' => array( 'immewnity', 'paladox2015', 'Craig Davison', 'lagleki' ),
	'license-name' => 'GPLv2+',
);

// Register files
$wgAutoloadClasses['SkinMetrolook'] = __DIR__ . '/SkinMetrolook.php';
$wgAutoloadClasses['MetrolookTemplate'] = __DIR__ . '/MetrolookTemplate.php';

// Register skin
$wgValidSkinNames['metrolook'] = 'Metrolook';

// Register modules
$wgResourceModules['skins.metrolook.styles'] = array(
	'styles' => array(
		'Metrolook/screen.css' => array( 'media' => 'screen' ),
		'Metrolook/screen-hd.css' => array( 'media' => 'screen and (min-width: 982px)' ),
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath' => &$GLOBALS['wgStyleDirectory'],
);
$wgResourceModules['skins.metrolook.js'] = array(
	'scripts' => array(
		'Metrolook/collapsibleTabs.js',
		'Metrolook/vector.js',
	),
	'position' => 'top',
	'dependencies' => array(
		'jquery.throttle-debounce',
		'jquery.tabIndex',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath' => &$GLOBALS['wgStyleDirectory'],
);
