<?php
/**
 * Metrolook - Metro look for website.
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
	'namemsg' => 'skinname-metrolook',
	'descriptionmsg' => 'metrolook-desc',
<<<<<<< HEAD
	'version' => '4.0 alpha 1',
=======
	'version' => '0.2.0',
>>>>>>> Improvements to Metrolook on MediaWiki 1.21
	'url' => 'https://www.mediawiki.org/wiki/Skin:Metrolook',
	'author' => array( 'immewnity', 'Paladox', 'Craig Davison', 'lagleki' ),
	'license-name' => 'GPLv2+',
);

// Register files
$wgAutoloadClasses['SkinMetrolook'] = __DIR__ . '/SkinMetrolook.php';
$wgAutoloadClasses['MetrolookTemplate'] = __DIR__ . '/MetrolookTemplate.php';

$wgExtensionMessagesFiles['MetrolookTemplate'] = __DIR__.'/Metrolook.i18n.php';

// Register skin
$wgValidSkinNames['metrolook'] = 'Metrolook';

// Configuration options
/**
 * Search form look.
 *  - true = use an icon search button
 *  - false = use Go & Search buttons
 */
$wgVectorUseSimpleSearch = true;

/**
 * Watch and unwatch as an icon rather than a link.
 *  - true = use an icon watch/unwatch button
 *  - false = use watch/unwatch text link
 */
$wgVectorUseIconWatch = true;


$wgMetrolookLogo = true;

$wgMetrolookSiteName = true;

/* to enable search bar on the sidebar and disables the search bar on the top bar */
$wgMetrolookSearchBar = true;

$wgMetrolookDownArrow = true;

$wgMetrolookLine = true;

$wgMetrolookUploadButton = true;

$wgMetrolookMobile = true;

/* To use tile 5 to 10 please diable this */
$wgMetrolookBartile = true;

$wgMetrolookTile1 = true;

$wgMetrolookTile2 = true;

$wgMetrolookTile3 = true;

$wgMetrolookTile4 = true;

// Register modules
$wgResourceModules['skins.metrolook'] = array(
	'styles' => array(
		'common/commonElements.css' => array( 'media' => 'screen' ),
		'common/commonContent.css' => array( 'media' => 'screen' ),
		'common/commonInterface.css' => array( 'media' => 'screen' ),
		'Metrolook/screen.css' => array( 'media' => 'screen' ),
		'Metrolook/screen-hd.css' => array( 'media' => 'screen and (min-width: 982px)' ),
		'Metrolook/collapsibleNav.css' => array( 'media' => 'screen' ),
		'Metrolook/mobile.css',
		'Metrolook/theme.css',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath' => &$GLOBALS['wgStyleDirectory'],
);
$wgResourceModules['skins.metrolook.js'] = array(
	'scripts' => array(
		'Metrolook/js/collapsibleTabs.js',
		'Metrolook/js/metrolook.js',
		'Metrolook/js/vector.js',
		'Metrolook/js/mediawiki.searchSuggest.custom.js',
		'Metrolook/js/overthrow.js',
	),
	'position' => 'top',
	'dependencies' => array(
		'jquery.delayedBind',
		'mediawiki.searchSuggest',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath' => &$GLOBALS['wgStyleDirectory'],
);
$wgResourceModules['skins.metrolook.collapsibleNav'] = array(
	'scripts' => array(
		'Metrolook/js/collapsibleNav.js',
	),
	'position' => 'bottom',
	'dependencies' => array(
<<<<<<< HEAD
		'jquery.client',
		'jquery.cookie',
		'jquery.tabIndex',
	),
	'remoteSkinPath' => 'Metrolook',
	'localBasePath' => __DIR__,
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
	'jquery.ui.menu' => 'skinStyles/jquery.ui/jquery.ui.menu.css',
	'jquery.ui.progressbar' => 'skinStyles/jquery.ui/jquery.ui.progressbar.css',
	'jquery.ui.resizable' => 'skinStyles/jquery.ui/jquery.ui.resizable.css',
	'jquery.ui.selectable' => 'skinStyles/jquery.ui/jquery.ui.selectable.css',
	'jquery.ui.slider' => 'skinStyles/jquery.ui/jquery.ui.slider.css',
	'jquery.ui.spinner' => 'skinStyles/jquery.ui/jquery.ui.spinner.css',
	'jquery.ui.tabs' => 'skinStyles/jquery.ui/jquery.ui.tabs.css',
	'jquery.ui.tooltips' => 'skinStyles/jquery.ui/jquery.ui.tooltips.css',
	'+mediawiki.notification' => 'skinStyles/mediawiki.notification.less',
	'+mediawiki.sectionAnchor' => 'skinStyles/mediawiki.sectionAnchor.less',
	'mediawiki.special' => 'skinStyles/mediawiki.special.less',
	'mediawiki.special.preferences' => 'skinStyles/mediawiki.special.preferences.less',
	'remoteSkinPath' => 'Metrolook',
	'localBasePath' => __DIR__,
=======
			'jquery.client',
			'jquery.cookie',
			'jquery.tabIndex',
		),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath' => &$GLOBALS['wgStyleDirectory'],
>>>>>>> Improvements to Metrolook on MediaWiki 1.21
);
