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

$GLOBALS['wgExtensionCredits']['skin'][] = array(
	'path' => __FILE__,
	'name' => 'Metrolook',
	'description' => 'Metrolook skin for MediaWiki.',
	'version' => '2.3.7',
	'url' => 'https://www.mediawiki.org/wiki/Skin:Metrolook',
	'author' => array( 'immewnity', 'paladox2015', 'Craig Davison', 'lagleki' ),
	'license-name' => 'GPLv2+',
);

// Register files
$GLOBALS['wgAutoloadClasses']['SkinMetrolook'] = __DIR__ . '/SkinMetrolook.php';
$GLOBALS['wgAutoloadClasses']['MetrolookTemplate'] = __DIR__ . '/MetrolookTemplate.php';

// Register skin
SkinFactory::getDefaultInstance()->register( 'metrolook', 'Metrolook', function(){
	$config = ConfigFactory::getDefaultInstance()->makeConfig( 'metrolook' );
	return new SkinMetrolook( $config );
} );

// Register config
$GLOBALS['wgConfigRegistry']['metrolook'] = 'GlobalVarConfig::newInstance';

// Configuration options
/**
 * Search form look.
 *  - true = use an icon search button
 *  - false = use Go & Search buttons
 */
$GLOBALS['wgVectorUseSimpleSearch'] = true;

/**
 * Watch and unwatch as an icon rather than a link.
 *  - true = use an icon watch/unwatch button
 *  - false = use watch/unwatch text link
 */
$GLOBALS['wgVectorUseIconWatch'] = true;

/* To enable logo. Note that if enabled it will not show properly.*/
$GLOBALS['Logoshow'] = false;

/* to enable search bar on the sidebar and disables the search bar on the top bar */
$GLOBALS['SearchBar'] = true;

// Register modules
$GLOBALS['wgResourceModules']['skins.metrolook.styles'] = array(
	'styles' => array(
		'screen.less' => array( 'media' => 'screen' ),
		'screen-hd.less' => array( 'media' => 'screen and (min-width: 982px)' ),
	),
	'remoteSkinPath' => 'Metrolook',
	'localBasePath' => __DIR__,
);
$GLOBALS['wgResourceModules']['skins.metrolook.js'] = array(
	'scripts' => array(
		'collapsibleTabs.js',
		'vector.js',
	),
	'position' => 'top',
	'dependencies' => array(
		'jquery.throttle-debounce',
		'jquery.tabIndex',
	),
	'remoteSkinPath' => 'Metrolook',
	'localBasePath' => __DIR__,
);
$GLOBALS['wgResourceModules']['skins.metrolook.collapsibleNav'] = array(
	'scripts' => array(
		'collapsibleNav.js',
	),
	'messages' => array(
		'vector-collapsiblenav-more',
	),
	'dependencies' => array(
		'jquery.client',
		'jquery.cookie',
		'jquery.tabIndex',
	),
	'remoteSkinPath' => 'Metrolook',
	'localBasePath' => __DIR__,
	'position' => 'bottom',
);

// Apply module customizations
$GLOBALS['wgResourceModuleSkinStyles']['metrolook'] = array(
	'jquery.tipsy' => 'skinStyles/jquery.tipsy.less',
	'jquery.ui.core' => array(
		'skinStyles/jquery.ui/jquery.ui.core.css',
		'skinStyles/jquery.ui/jquery.ui.theme.css',
	),
	'jquery.ui.accordion' => 'skinStyles/jquery.ui/jquery.ui.accordion.css',
	'jquery.ui.autocomplete' => 'skinStyles/jquery.ui/jquery.ui.autocomplete.css',
	'jquery.ui.button' => 'skinStyles/jquery.ui/jquery.ui.button.css',
	'jquery.ui.datepicker' => 'skinStyles/jquery.ui/jquery.ui.datepicker.css',
	'jquery.ui.dialog' => 'skinStyles/jquery.ui/jquery.ui.dialog.css',
	'jquery.ui.progressbar' => 'skinStyles/jquery.ui/jquery.ui.progressbar.css',
	'jquery.ui.resizable' => 'skinStyles/jquery.ui/jquery.ui.resizable.css',
	'jquery.ui.selectable' => 'skinStyles/jquery.ui/jquery.ui.selectable.css',
	'jquery.ui.slider' => 'skinStyles/jquery.ui/jquery.ui.slider.css',
	'jquery.ui.tabs' => 'skinStyles/jquery.ui/jquery.ui.tabs.css',
	'mediawiki.notification' => 'skinStyles/mediawiki.notification.less',
	'mediawiki.special' => 'skinStyles/mediawiki.special.less',
	'mediawiki.special.preferences' => 'skinStyles/mediawiki.special.preferences.less',
	'remoteSkinPath' => 'Metrolook',
	'localBasePath' => __DIR__,
);
