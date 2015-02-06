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

$GLOBALS['wgExtensionCredits']['skin'][] = array(
	'path' => __FILE__,
	'name' => 'Metrolook',
	'namemsg' => 'skinname-metrolook',
	'descriptionmsg' => 'metrolook-desc',
	'version' => '0.3.12',
	'url' => 'https://www.mediawiki.org/wiki/Skin:Metrolook',
	'author' => array( 'immewnity', 'Paladox', 'Craig Davison', 'lagleki' ),
	'license-name' => 'GPLv2+',
);

// Register files
$GLOBALS['wgAutoloadClasses']['MetrolookHooks'] = __DIR__ . '/SkinMetrolookHooks.php';
$GLOBALS['wgAutoloadClasses']['SkinMetrolook'] = __DIR__ . '/SkinMetrolook.php';
$GLOBALS['wgAutoloadClasses']['MetrolookTemplate'] = __DIR__ . '/MetrolookTemplate.php';

$GLOBALS['wgHooks']['BeforePageDisplay'][] = 'MetrolookHooks::beforePageDisplay';
$GLOBALS['wgHooks']['GetPreferences'][] = 'MetrolookHooks::getPreferences';
$GLOBALS['wgHooks']['ResourceLoaderGetConfigVars'][] = 'MetrolookHooks::resourceLoaderGetConfigVars';
$GLOBALS['wgHooks']['MakeGlobalVariablesScript'][] = 'MetrolookHooks::makeGlobalVariablesScript';

$GLOBALS['wgExtensionMessagesFiles']['MetrolookTemplate'] = __DIR__.'/Metrolook.i18n.php';

// Register skin
$GLOBALS['wgValidSkinNames']['metrolook'] = 'Metrolook';

// Configuration options

// Each module may be configured individually to be globally on/off or user preference based
$GLOBALS['wgMetrolookFeature']s = array(
	'collapsiblenav' => array( 'global' => false, 'user' => true ),
);

/* 
 * Setting default option.
 *
 * Do not remove this.
 *
 * Bug T76314
 *
 * You can add this to your localsettings.php file and change from 1 to 0 to disbale it globaly.
 */
$GLOBALS['wgDefaultUserOptions']['skinmetrolook-collapsiblenav'] = 1;

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

/**
 * Logo
 *  - true = Logo will show
 *  - false = Logo will not show
 */

$GLOBALS['wgMetrolookLogo'] = true;

$GLOBALS['wgMetrolookSiteName'] = true;

/* to enable search bar on the sidebar and disables the search bar on the top bar */
$GLOBALS['wgMetrolookSearchBar'] = true;

$GLOBALS['wgMetrolookDownArrow'] = true;

$GLOBALS['wgMetrolookLine'] = true;

$GLOBALS['wgMetrolookUploadButton'] = true;

$GLOBALS['wgMetrolookMobile'] = true;

/* To use tile 5 to 10 please diable this */
$GLOBALS['wgMetrolookBartile'] = true;

$GLOBALS['wgMetrolookTile1'] = true;

$GLOBALS['wgMetrolookTile2'] = true;

$GLOBALS['wgMetrolookTile3'] = true;

$GLOBALS['wgMetrolookTile4'] = true;

// Register modules
$GLOBALS['wgResourceModules']['skins.metrolook'] = array(
	'styles' => array(
		'common/commonElements.css' => array( 'media' => 'screen' ),
		'common/commonContent.css' => array( 'media' => 'screen' ),
		'common/commonInterface.css' => array( 'media' => 'screen' ),
		'Metrolook/styles.less',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath' => &$GLOBALS['wgStyleDirectory'],
);
$GLOBALS['wgResourceModules']['skins.metrolook.beta'] = array(
	'styles' => array(
		'common/commonElements.css' => array( 'media' => 'screen' ),
		'common/commonContent.css' => array( 'media' => 'screen' ),
		'common/commonInterface.css' => array( 'media' => 'screen' ),
		'Metrolook/styles-beta.less',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath' => &$GLOBALS['wgStyleDirectory'],
);
$GLOBALS['wgResourceModules']['skins.metrolook.js'] = array(
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
$GLOBALS['wgResourceModules']['skins.metrolook.collapsibleNav'] = array(
	'scripts' => array(
		'Metrolook/js/collapsibleNav.js',
	),
	'styles' => array(
		'Metrolook/collapsibleNav.css',
	),
	'position' => 'bottom',
	'dependencies' => array(
		'jquery.client',
		'jquery.cookie',
		'jquery.tabIndex',
	),
	'remoteBasePath' => &$GLOBALS['wgStylePath'],
	'localBasePath' => &$GLOBALS['wgStyleDirectory'],
);
